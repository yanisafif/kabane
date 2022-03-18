window.post = function(url, data) {
    return fetch(url, {method: "POST", headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, body: JSON.stringify(data)});
}

const modalContainer =  document.getElementById('modal-container')
let kanban
(function() {
    const boards = new Array()
    const data = JSON.parse(document.getElementById('dataCols').textContent)
    console.log(data)
    const style = document.createElement('style')
    for(col of data)
    {
        style.innerHTML += `
            .col${col.id} {
                background-color: ${col.colorHexa};
                color: ${figureTextColor(col.colorHexa)};
            }
        `
        const board = {
            id: '_col' + col.id,
            title: col.name,
            class: 'col' + col.id,
            item: new Array()
        }

        for(item of col.items)
        {
            board.item.push(createItem(item))
        }

        boards.push(board)
        i++
    }

    
    kanban = new jKanban({
        element: '#kabane',
        gutter: '15px',
        click: function (el) {
            el.class
        },
        boards: boards,
        itemAddOptions: {
            enabled: true,                                              // add a button to board for easy item creation
            content: '+ Add item',                                                // text or html content of the board button
            class: 'kanban-title-button btn btn-default text-center w-100',         // default class of the button
            footer: true                                                // position the button on footer
        },
        buttonClick: (el, boardId) => {
            console.log(el, boardId)
            displayCreateModal(boardId)
        }
    });
    document.getElementsByTagName('head')[0].appendChild(style)
})();

function displayCreateModal(colId) {
    const modal = document.createElement('div')
    modal.innerHTML = `
    <div class="modal fade" name="creation-modal" id="creation-modal" role="dialog" aria-labelledby="creation-modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New item</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="creation-form">
                        <div class="mb-3">
                            <label class="col-form-label" for="recipient-name">Title:</label>
                            <input class="form-control" name="title" type="text" value="">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label" for="recipient-name">Assigned:</label>
                            <select class="form-select" name="assign" id="select-people" aria-label="Default select example">
                                <option value="-1"> Unassigned </option> 
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label" for="message-text">Description:</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary"  type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="modal-submit-btn" type="button">Send message</button>
                </div>
            </div>
        </div>
    </div>
    `

    modalContainer.appendChild(modal)
    const select = document.getElementById('select-people')

    const people = JSON.parse(document.getElementById('dataPeople').textContent)

    for(const person of people) {
        const option = document.createElement('option')
        option.textContent = person.name
        option.value = person.id
        select.appendChild(option)
    }

    const submitBtn = document.getElementById('modal-submit-btn')
    
    submitBtn.onclick = () => {
        const formData = $('#creation-form').serializeArray()
        console.log(formData)
        const name = formData[0].value
        const description = formData[2].value
        
        kanban.addElement(colId, 
            createItem({
                created_at: new Date().toDateString(), 
                item_name: name, 
                description,
            })
        );

        window.post('/kanban/store-item', {
            name, 
            description, 
            colId: parseInt(colId.substring(4))
        })
        .then((res) => {
            console.log(res)
            kanban.addElement(colId, 
                createItem({
                    created_at: new Date().toDateString(), 
                    item_name: name, 
                    description,
                })
            );
            $("#creation-modal").modal('hide'); 
        })
        .catch((err) => {
            console.log(err)
        })

    }

    $("#creation-modal").modal('show');

    $('#creation-modal').on('hidden.bs.modal', function (e) {
        console.log('here', e);
        modalContainer.removeChild(modal);
    })
}


function createItem(item) {
    const dateDisplay = new Date(Date.parse(item.created_at)).toLocaleDateString('en-GB', { day: "numeric", month: 'short', year: 'numeric' });
    return {
        title: `<a class="kanban-box overflow-hidden" style="max-height: 150px" href="#">
            <div class="row">
                <div class="col">
                    <span >${dateDisplay}</span>
                    <h6>${item.item_name}</h6>
                </div>
                <div class="col text-end">
                    ${item.ownerUser_name ? 'Assigned to: ' +item.ownerUser_name : 'Unassigned' } 
                </div>
            </div>
            <div class="d-flex mt-2 overflow-hidden" stye>
                ${item.description}
            </div>
        </a>`
    }; 
}

function figureTextColor(bgColor) {
    var color = (bgColor.charAt(0) === '#') ? bgColor.substring(1, 7) : bgColor;
    var r = parseInt(color.substring(0, 2), 16); // hexToR
    var g = parseInt(color.substring(2, 4), 16); // hexToG
    var b = parseInt(color.substring(4, 6), 16); // hexToB
    var uicolors = [r / 255, g / 255, b / 255];
    var c = uicolors.map((col) => {
        if (col <= 0.03928) {
        return col / 12.92;
        }
        return Math.pow((col + 0.055) / 1.055, 2.4);
    });
    var L = (0.2126 * c[0]) + (0.7152 * c[1]) + (0.0722 * c[2]);
    return (L > 0.179) ? '#000' : '#fff';
}
