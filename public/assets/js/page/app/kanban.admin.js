(function() {
    setUpAddCol()
    setUpDeleteColumn()
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