window.httpRequest = function(url, method, data) {
    return fetch(url, {method, headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, body: JSON.stringify(data)});
}

const modalContainer =  document.getElementById('modal-container')
let kanban, data, people

(function() {
    const dataCols = document.getElementById('dataCols')
    data = JSON.parse(dataCols.textContent)
    dataCols.parentNode.removeChild(dataCols)
    console.log(data)

    const dataPeople = document.getElementById('dataPeople')  
    people = JSON.parse(dataPeople.textContent)  
    dataPeople.parentNode.removeChild(dataPeople)
    console.log(people)

    const boards = new Array()
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
            board.item.push(createItem(item, col.id))
        }
        console.log(board)
        boards.push(board)
        i++
    }


    kanban = new jKanban({
        element: '#kabane',
        gutter: '15px',
        click: (el) => {
            // Get id
            displayItemDetailsModal(el);

        },
        boards: boards,
        itemAddOptions: {
            enabled: true,                                              // add a button to board for easy item creation
            content: '+ Add item',                                                // text or html content of the board button
            class: 'kanban-title-button btn btn-default text-center w-100',         // default class of the button
            footer: true                                                // position the button on footer
        },
        buttonClick: (el, boardId) => {
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
                    <span class="text-danger" id="form-error-label"></span>
                    <form id="creation-form">
                        <div class="mb-3">
                            <label class="col-form-label" for="title">Title:</label>
                            <input class="form-control" required="true" name="title" maxlength="50" type="text" value="">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label" for="assign">Assigned:</label>
                            <select class="form-select" name="assign" id="select-people-creation" aria-label="Select assign person">
                                <option value="-1"> Unassigned </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label" for="deadline">Deadline:</label>
                            <input class="form-control" name="deadline" min="${new Date().toISOString().substring(0, 10)}" type="date">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label" for="description">Description:</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary"  type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="modal-creation-submit-btn" type="button">Save item</button>
                </div>
            </div>
        </div>
    </div>
    `

    modalContainer.appendChild(modal)
    const select = document.getElementById('select-people-creation')

    for(const person of people) {
        const option = document.createElement('option')
        option.textContent = person.name
        option.value = person.id
        select.appendChild(option)
    }

    const submitBtn = document.getElementById('modal-creation-submit-btn')

    submitBtn.onclick = () => {
        $('#form-error-label').text('')
        const formData = $('#creation-form').serializeArray()
        console.log(formData)
        const name = formData[0].value
        const assign = parseInt(formData[1].value)
        const deadline = formData[2].value
        const description = formData[3].value
        
        httpRequest('/item/store', 'POST', {
            name,
            description,
            assign,
            deadline: deadline ?? null,
            colId: parseInt(colId.substring(4))
        })
        .then(async (res) => {

            const json = await res.json()
            console.log(json, res)

            if(!res.ok) {
                if(res.status) {
                    $('#form-error-label').text(json.status)
                }
                else {
                    $('#form-error-label').text('An error occurred')
                }
                return
            }

            // const col = data.find( f => f.id === parseInt(colId.substring(4)))
            // col.items.push({})

            kanban.addElement(colId,
                createItem({
                    created_at: new Date().toDateString(),
                    item_name: name,
                    description,
                    deadline, 
                    assignedUser_name: assign > 0 ? people.find(f => f.id === assign).name : '',
                    item_id: json.itemId
                })
            );
            $("#creation-modal").modal('hide')
        })
    }

    $("#creation-modal").modal('show');

    $('#creation-modal').on('hidden.bs.modal', function () {
        modalContainer.removeChild(modal);
    })
}

function displayItemDetailsModal(el) {
    const htmlId = el.getElementsByClassName('kanban-box')[0].id 
    const idSplitted = htmlId.split('-')
    const itemId = parseInt(idSplitted[1])
    const colId = parseInt(idSplitted[2])

    const colJson = data.find(f => f.id === colId)
    console.log(colJson)
    const itemJson = colJson.items.find(f => f.item_id === itemId)
    console.log(itemJson)

    const modal = document.createElement('div')
    modal.innerHTML = `
        <div class="modal fade" name="modification-modal" id="modification-modal" role="dialog" aria-labelledby="modification-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="edit-form">
                    <div class="modal-header">
                        <h5 class="w-75 mb-0">
                            <input type="text" name="title" value="asdf" readonly="true" maxlength="50" class="w-100"
                                ondblclick="this.readOnly=''; this.style.border = '1px solid'"
                                onfocusout="this.readOnly='true'; this.style.border = 'none'"
                                style="border: none">
                        </h5>
                        <button class="btn-close position-static" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span class="date">Created: ${getDateToDisplay(itemJson.created_at)} </span>
                        <span class="date d-inline-block" style="padding-inline: 10px"> Modified: ${getDateToDisplay(itemJson.updated_at)} </span>
                        <div class="mb-3">
                            <label class="col-form-label" for="recipient-name">Assigned:</label>
                            <select class="form-select" name="assign" id="select-people-edit" aria-label="Default select example">
                                <option value="-1"> Unassigned </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label" for="recipient-name">Deadline:</label>
                            <input class="form-control" name="title" type="date" value="${itemJson.deadline ?? ''}">
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label" for="message-text">Description:</label>
                            <textarea class="form-control" name="description"> ${itemJson.description ?? ''} </textarea>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" id="item-delete-btn">Delete</button>
                    <button class="btn btn-secondary"  type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" id="modal-edit-submit-btn" type="button">Send message</button>
                </div>
            </div>
        </div>
    `
    modalContainer.appendChild(modal)

    for(const person of people) {
        const option = document.createElement('option')
        option.value = person.id
        option.textContent = person.name
        document.getElementById('select-people-edit').appendChild(option)
    }
    $('#select-people-edit').val(itemJson.assignedUser_id ?? -1)

    $('#modification-modal').on('hidden.bs.modal',  () => {
        modalContainer.removeChild(modal)
    })

    document.getElementById('item-delete-btn').onclick = function () {
        
        httpRequest('/item/delete', 'DELETE', { itemId })
            .then((res) => {

                if(!res.ok) {
                    return
                }
    
                el.parentNode.removeChild(el)
                colJson.items.splice(colJson.items.indexOf(itemJson), 1)
                console.log(data);
                $("#modification-modal").modal('hide')
            })
    }
    
    $("#modification-modal").modal('show')

}


function createItem(item, colId) {
    return {
        title: `<a id="item-${item.item_id}-${colId}" class="kanban-box overflow-hidden"style="max-height: 150px" href="#">
            <div class="row">
                <div class="col">
                    <span >${getDateToDisplay(item.created_at)}</span>
                    <h6>${item.item_name}</h6>
                </div>
                <div class="col text-end">
                    ${item.assignedUser_name ? 'Assigned to: ' + item.assignedUser_name : 'Unassigned'}
                </div>
            </div>
            <div class="d-flex mt-2 overflow-hidden">
                ${item.description  ?? ''}
            </div>
        </a>
        `
    };
}

function getDateToDisplay(dateString) {
    return new Date(Date.parse(dateString))
        .toLocaleDateString('en-GB', { day: "numeric", month: 'short', year: 'numeric' })
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
