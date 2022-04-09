(function() {

    // $('#settings-access-btn').click(() => {
        
    //     $('#settings-modal').modal('show')
    // })
    setUpAddCol()

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
            }
        })
    })


}