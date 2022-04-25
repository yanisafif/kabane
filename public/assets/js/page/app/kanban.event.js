setUpEvent()


function setUpEvent() {
    
    const currentUserId = window.people.find(f => f.isCurrentUser).id;

    window.Echo.private('kanban.' + window.kanbanId)
        .listen('NewItem', res => {
            if(res.actionMadeByUserId === currentUserId) {
                return
            }
            
            const col = window.data.find(f => f.id === res.colId)

            const newItem = parseItem(res)

            window.kanban.addElement('_col' + col.id, window.createItem(newItem, col.id))
            col.items.push(newItem)
        })
        .listen('UpdatedItem', res => {
            if(res.actionMadeByUserId === currentUserId) {
                return
            }
            
            const col = window.data.find(f => f.id === res.colId)
            const item = col.items.find(f => f.item_id === res.item_id) 

            const newItem = parseItem(res)

            for(const key in newItem) {
                item[key] = newItem[key]
            }

            // const htmlItemEl = $(`div[data-eid='item-${item.item_d}']`)
    
            window.kanban.replaceElement(`item-${item.item_id}`, window.createItem(item, col.id))
        })
        .listen('DeletedItem', res =>  {
            if(res.actionMadeByUserId === currentUserId) {
                return
            }

            $(`div[data-eid=item-${res.item_id}]`).remove()

            const col = window.data.find(f => f.id === res.colId)
            const itemToDel = col.items.find(f => f.item_id === res.item_id)
            col.items.splice(col.items.indexOf(itemToDel))
        }) 
    
} 

function parseItem(res) {
    const newItem = {
        created_at: res.created_at, 
        deadline: res.deadline, 
        description: res.description, 
        item_id: res.item_id, 
        item_name: res.name, 
        updated_at: res.updated_at,
        
        ownerUser_id: res.ownerUserId, 
        ownerUser_name: window.people.find(f => f.id === res.ownerUserId)?.name, 
        
        assignedUser_id: res.assignedUserId
    }

    if(newItem.assignedUser_id) {
        newItem.assignedUser_name = window.people.find(f => f.id === newItem.assignedUser_id)?.name
    }

    return newItem
}