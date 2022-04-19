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
                    txtColor: window.figureTextColor(dataToSend.colorHexa),
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

    $('#settings-invite-btn').click(() => {
        $('#settings-invite-error-message').text('')

        const usernameInvitation = $('#settings-name-field').val()
        console.log('Send invite', usernameInvitation)
        
        window.httpRequest('/kanban/invite', 'POST', {
            kanbanId: window.kanbanId, 
            username: usernameInvitation
        }).then(async (res) => {
            
            const json = await res.json()
            console.log(json)

            if(!res.ok) {
                $('#settings-invite-error-message').text(json.status ?? 'An error occurred')
                return
            }

            $('#settings-name-field').val('')

            const settingFormEl = document.createElement('div'); 
            settingFormEl.classList.add('p-2', 'settings-person-container', 'd-flex')
            settingFormEl.innerHTML = `
                <div class="setting-person-name">${json.username}</div>
                <img class="setting-person-uninvite" data-id="${json.userId}" src="${window.location.protocol +'//'+ window.location.hostname}/assets/svg/trash.svg">
            `
            document.getElementById('settings-people-list-container').appendChild(settingFormEl);

            window.people.push({ 
                id: json.userId, 
                name: json.username,
                isCurrentUser: false
            })

            const itemFormEl = document.createElement('option')
            itemFormEl.value = json.userId
            itemFormEl.textContent = json.username
            document.getElementById('select-people-creation').appendChild(itemFormEl)
            document.getElementById('edit-form-select-people').appendChild(itemFormEl.cloneNode(true))
        })

    })
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