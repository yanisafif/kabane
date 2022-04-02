window.httpRequest = function(url, method, data) {
    return fetch(url, {method, headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, body: JSON.stringify(data)});
}

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
            title: ` 
                <input type="text" name="item_name" class="rounded-1 w-100 title-col" 
                    readonly="true" maxlength="50"
                    ondblclick="onTitleDbClick(this)"
                    onfocusout="onTileFocusOut(this)"
                    onkeyup="onTitleKeyUp(this, event)"
                    data-id="${col.id}"
                    style="border: none; background: transparent" value="${col.name}">
            `,
            class: 'col' + col.id,
            item: new Array()
        }

        for(item of col.items)
        {
            board.item.push(createItem(item, col.id))
        }
        boards.push(board)
    }

    kanban = new jKanban({
        element: '#kabane',
        gutter: '15px',
        boards: boards,
        dragBoards: false,   
        itemAddOptions: {
            enabled: true,                                              // add a button to board for easy item creation
            content: '+ Add item',                                                // text or html content of the board button
            class: 'kanban-title-button btn btn-default text-center w-100',         // default class of the button
            footer: true                                                // position the button on footer
        },
        buttonClick: (el, boardId) => {
            displayCreateModal(boardId)
        },
        click: (el) => {
            displayItemDetailsModal(el)
        },
        dropEl: (el, target, source) => {
            moveItem(el, target, source)
        }
    });

    // Add style classes
    document.getElementsByTagName('head')[0].appendChild(style)

    // On modal create close clear fields and events
    $('#creation-modal').on('hidden.bs.modal', function () {
        $('.create-inputs').val('')
        $('#select-people-creation').val('-1')

        $('#modal-creation-submit-btn').unbind('click')
    })

    // On modal edit close clear fields and events
    $('#modification-modal').on('hidden.bs.modal', () => {
        $('.edit-from-inputs').val('')
        $('#edit-form-select-people').val('-1')
        $('.edit-form-date').text('')

        $('#item-delete-btn').unbind('click')
        $('#modal-edit-submit-btn').unbind('click')
    })

})();

//#region Handle title modifications 

// Trigger on cols' input double click
function onTitleDbClick(thisEl) {
    // Enable modification
    thisEl.readOnly=''; 
    thisEl.style.border = '2px solid'
    thisEl.style.background = ''

    thisEl.dataset.oldValue = thisEl.value
}

// Trigger on keyup
function onTitleKeyUp(thisEl, e) {

    // If equals 'Enter' then handle edit finish
    if(e.keyCode === 13) {
        handleTitleFinishEdit(thisEl)
    }
}

// Trigger on cols' input focus out 
function onTileFocusOut(thisEl) {
    handleTitleFinishEdit(thisEl)
}

function handleTitleFinishEdit(thisEl) {
    thisEl.readOnly = 'true'
    thisEl.style.border = 'none'
    thisEl.style.background = 'transparent'


    // New value empty, undo modification 
    if(!thisEl.value) {
        thisEl.value = thisEl.dataset.oldValue 
        return
    }

    // No changes made 
    if(thisEl.dataset.oldValue === thisEl.value) {
        return
    }

    // Make request
    httpRequest('/col/rename', 'PUT', {
        colId: thisEl.dataset.id, 
        colName: thisEl.value
    })
}
//#endregion

function displayCreateModal(colId) {

    // On submit
    $('#modal-creation-submit-btn').click(() => {
        $('#form-error-label').text('')
        
        // Get data from form
        const dataForm = {}
        for(input of $('#creation-form').serializeArray()) {
            dataForm[input.name] = input.value ?? null
        }
        dataForm.assignedUser_id = parseInt(dataForm.assignedUser_id)
        dataForm.colId = parseInt(colId.substring(4))
        
        // Send add request 
        httpRequest('/item/store', 'POST', dataForm)
            .then(async (res) => {

                const json = await res.json()
                
                // Handle request failure
                if(!res.ok) {
                    if(res.status) {
                        $('#form-error-label').text(json.status)
                    }
                    else {
                        $('#form-error-label').text('An error occurred')
                    }
                    return
                }
                
                // Gather needed data to create an item in the board
                const col = data.find(f => f.id === dataForm.colId)
                const assigned = people.find(f => f.id === dataForm.assignedUser_id)
                const owner = people.find(f => f.isCurrentUser)
                const now = new Date().toDateString()

                // Create item 
                const item = {
                    assignedUser_name: (assigned ? assigned.name : null),
                    assignedUser_id: (assigned ? assigned.id : null),
                    created_at: now, 
                    deadline: dataForm.deadline, 
                    description: dataForm.description, 
                    itemOrder: 1, 
                    item_id: parseInt(json.item_id), 
                    item_name: dataForm.item_name, 
                    ownerUser_name: owner.name, 
                    ownerUser_id: owner.id,
                    updated_at: now
                }
                
                col.items.push(item) // Add item to main object 'data' 

                kanban.addElement(colId, createItem(item, col.id)) // Create html item and add it to the board

                $("#creation-modal").modal('hide')
            })
    })

    $("#creation-modal").modal('show') // Show modal
}

function displayItemDetailsModal(el) {

    // Get ids for html id. Pattern: 'item-$itemId-$colId
    const htmlId = el.getElementsByClassName('kanban-box')[0].id 
    const idSplitted = htmlId.split('-')
    const itemId = parseInt(idSplitted[1])
    const colId = parseInt(idSplitted[2])

    // Gather needed data
    const colData = data.find(f => f.id === colId)
    const itemData = colData.items.find(f => f.item_id === itemId)

    // Set form modal fields
    $('#edit-form-title').val(itemData.item_name)
    $('#edit-form-deadline').val(itemData.deadline ?? '')
    $('#edit-form-description').val(itemData.description ?? '')
    $('#edit-form-select-people').val(itemData.assignedUser_id ?? -1)
    $('#edit-form-created').text(getDateToDisplay(itemData.created_at))
    $('#edit-form-modified').text(getDateToDisplay(itemData.updated_at))

    // On delete button click 
    $('#item-delete-btn').click(() => {
        
        // Send delete request
        httpRequest('/item/delete', 'DELETE', { itemId })
            .then((res) => {

                // Handle request failure
                if(!res.ok) {
                    $("form-edit-error-label").text('An error occurred')
                    return
                }
                
                // Remove item form board and data object
                el.parentNode.removeChild(el)
                colData.items.splice(colData.items.indexOf(itemData), 1)

                $("#modification-modal").modal('hide') // Close modal
            })
    })

    // On modal save 
    $('#modal-edit-submit-btn').click(() => {

        const dataForm = {}
        
        // Get data from modal form
        for(input of $('#edit-form').serializeArray()) {
            dataForm[input.name] = input.value || null
        }
    
        // Parse data
        if(dataForm.assignedUser_id === '-1') {
            dataForm.assignedUser_id = null
        }
        else {
            dataForm.assignedUser_id = parseInt(dataForm.assignedUser_id)
        }
        
        const requestBody = {
            itemId: itemData.item_id
        }

        // Build request, insert only modified elements
        for(const key in dataForm) {

            if(dataForm[key] !== itemData[key]) {
                requestBody[key] = dataForm[key]
            }
        }

        // If nothing has been modified then close modal
        if(Object.keys(requestBody).length === 1) {
            $("#modification-modal").modal('hide')
            return
        }

        // Send update request
        httpRequest('/item/update', 'PUT', requestBody)
            .then(async (res) => {

                // Handle request failure
                if(!res.ok) {
                    $("#form-edit-error-label").text('An error occurred')
                    return
                }

                for(const key in requestBody) {
                    itemData[key] = requestBody[key]
                }

                kanban.replaceElement(el, createItem(itemData, colData.id))
                
                $("#modification-modal").modal('hide')
            })
    })

    $("#modification-modal").modal('show') // Show modal 
}

function moveItem(el, target, source) {

    // Get col id
    const sourceId = parseInt(source.parentNode.getAttribute('data-id').substring(4))
    const targetId = parseInt(target.parentNode.getAttribute('data-id').substring(4))

    // Modify colId in html item id 
    const itemAEl = el.getElementsByTagName('a')[0]
    const itemId = parseInt(itemAEl.id.split('-')[1])
    itemAEl.id = `item-${itemId}-${targetId}`

    // Get data columns
    const dataColSource = data.find(f => f.id === sourceId)
    const dataColTarget = data.find(f => f.id === targetId)

    // Get moved item
    const dataItem = dataColSource.items.find(f =>  f.item_id ===  itemId)

    // Send request
    httpRequest('/item/move', 'PUT', {
        itemId, 
        targetCol: targetId
    }).then((res) => {
        
        // Move item in the object 'data'
        if(res.ok) {
            dataColTarget.items.push(dataItem)
            dataColSource.items.splice(dataColSource.items.indexOf(dataItem), 1)
        }
    })
    
}


function createItem(item, colId) {
    return {
        id: `item-${item.item_id}`,
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
    }
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
