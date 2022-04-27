$('#self-uninvite-btn').click(() => {

    httpRequest('/kanban/self-uninvite', 'DELETE', { 
        kanbanId: window.kanbanId 
    }).then(async res => {
        
        if(!res.ok) {
            return
        }

        window.location.replace(`${window.location.protocol}//${window.location.hostname}/kanban/board`)
    })
})
