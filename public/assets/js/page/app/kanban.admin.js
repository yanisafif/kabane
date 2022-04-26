(function() {
    setUpAddCol()
    setUpDeleteColumn()
    setUpSettingsConsole()
})()

function setUpAddCol() {

    $('#add-col-btn').click(() => {
        const dataToSend = {
            kanbanId: window.kanbanId, 
            colOrder: window.data.length + 1, 
            colName: `Column ${window.data.length + 1}`, 
            colorHexa: '#24695c'
        };
        
        window.httpRequest('/col/add', 'POST', dataToSend).then( async(res) => {
            
            const json = await res.json();

            if(res.ok && json?.itemId) {

                const col = {
                    colOrder: dataToSend.colOrder, 
                    colorHexa: dataToSend.colorHexa, 
                    name: dataToSend.colName,
                    id: json.itemId, 
                    txtColor: '#fff',
                    items: new Array()
                }

                window.kanban.addBoards([window.createBoard(col)])
                window.data.push(col)

                const colDomEl = window.kanban.findBoard(`_col${col.id}`)
                const colorField = colDomEl.querySelector('.col-header-color-btn')

                window.setColHeaderColor(col.id, col.colorHexa, col.txtColor)
                window.createColorPicker(colorField, col)
                wireColDeleteBtn(colDomEl.querySelector('.col-header-delete-btn'))
            }
        })
    })
}

function setUpDeleteColumn() {
    
    const deleteBtns = document.getElementsByClassName('col-header-delete-btn')
    
    for(const deleteBtn of deleteBtns) {
        wireColDeleteBtn(deleteBtn)
    }

    $('#delete-col-modal').on('hidden.bs.modal', () => {
        $('#modal-delete-col-name').text('')
        $('#modal-delete-col-yes-btn').unbind('click')
    })
}

function setUpSettingsConsole() {

    $('#settings-access-btn').click(() => {
        $('#settings-modal').modal('show')
    })

    // Wire uninvite person btns
    for(const el of document.getElementsByClassName('setting-person-uninvite')) {
        wirePersonUninvite(el)
    }

    $('#settings-invite-btn').click(() => {
        $('#settings-invite-error-message').text('')

        const nameOrEmail = $('#settings-name-field').val()
        console.log('Send invite', nameOrEmail)
        
        window.httpRequest('/kanban/invite', 'POST', {
            kanbanId: window.kanbanId, 
            nameOrEmail
        }).then(async (res) => {
            
            const json = await res.json()
            console.log(json)

            if(!res.ok) {
                $('#settings-invite-error-message').text(json.status ?? 'An error occurred')
                return
            }

            $('#settings-name-field').val('')
        
            const person = { 
                id: json.userId, 
                name: json.username,
                path_image: json.path_image,
                isCurrentUser: false
            }
            window.people.push(person)

            const settingFormEl = document.createElement('div'); 
            settingFormEl.classList.add('p-2', 'settings-person-container', 'd-flex')
            settingFormEl.innerHTML = `
                <div class="setting-person-name">${window.getUserDisplay(person)}</div>
                <img class="setting-person-uninvite" data-id="${json.userId}" src="${window.location.protocol +'//'+ window.location.hostname}/assets/svg/trash.svg">
            `
            document.getElementById('settings-people-list-container').appendChild(settingFormEl)
            wirePersonUninvite(settingFormEl.querySelector('.setting-person-uninvite'))
            
            // Add person to array people
            
            if(window.people.length >= 2) {
                $("#settings-noinvited-message").addClass('d-none')
            }
            
            const itemFormEl = document.createElement('option')
            itemFormEl.value = json.userId
            itemFormEl.textContent = json.username
            document.getElementById('select-people-creation').appendChild(itemFormEl)
            document.getElementById('edit-form-select-people').appendChild(itemFormEl.cloneNode(true))
        })
    })
}

function wirePersonUninvite(deleteBtn) {

    deleteBtn.onclick = () => {
        
        const userId = parseInt(deleteBtn.dataset.id)
        
        window.httpRequest('/kanban/uninvite', 'DELETE', {
            userId, 
            kanbanId: window.kanbanId
        })
        .then((res) => {
            
            if(!res.ok) {
                return
            }

            // Remove person form array 'people'
            window.people.splice(window.people.findIndex(f => f.id === userId), 1)

            if(window.people.length === 1) {
                $("#settings-noinvited-message").removeClass('d-none')
            }

            
            $('#edit-form-select-people').find(`option[value='${userId}']`).remove()
            $('#select-people-creation').find(`option[value='${userId}']`).remove()

            const userEl = deleteBtn.parentNode
            userEl.parentNode.removeChild(userEl)
        })
    }
}

function wireColDeleteBtn(deleteBtn) {
    deleteBtn.onclick = () => {

        const colId = parseInt(deleteBtn.parentNode.dataset.id)
        const colObj = window.data.find(f => f.id === colId)
        
        $('#modal-delete-col-name').text(colObj.name)
        
        $('#modal-delete-col-yes-btn').click(() => {
            
            window.kanban.removeBoard('_col' + colId)    
            const arrayToSend = window.updateAndGetColOrder()
            
            httpRequest('/col/delete', 'DELETE', {  
                kanbanId: window.kanbanId, 
                deleteColId: colId, 
                cols: arrayToSend
                
            }).then((res) => {
                console.log(res)
            })

            window.data.splice(window.data.indexOf(colObj), 1)            
            $('#delete-col-modal').modal('hide')
        })

        $('#delete-col-modal').modal('show')
    }
}