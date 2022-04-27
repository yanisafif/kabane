
(function() {
    const datasetDiv = document.getElementById('dataset')
    window.kanbanId = parseInt(datasetDiv.dataset.kanbanid)
    window.people = JSON.parse(datasetDiv.textContent)

    datasetDiv.parentElement.removeChild(datasetDiv)
    
    scrollChatToBottom()
    setUpSendMessage()
    setUpWebsocket()
})();

window.httpRequest = function(url, method, data) {
    return fetch(url, {method, headers: {'Content-Type': 'application/json','accept': 'application/json','X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, body: JSON.stringify(data)});
}

function setUpSendMessage() {
    const currentUser = window.people.find(f => f.isCurrentUser)
    $('#message-send-btn').click(() => {
        $('#message-error').addClass('d-none')

        const messageContent = $('#message-to-send').val()

        httpRequest('/message/add', 'POST', {
            content: messageContent, 
            kanbanId: window.kanbanId
        }).then(async res => {

            const json = await res.json()
            if(!res.ok) {
                const errorField = $('#message-error')
                errorField.text(json.status ?? 'An error occurred')
                errorField.removeClass('d-none')
                return
            }
            const now = new Date()
            const date = dateToString(now)
            addRenderMessage(messageContent, currentUser, date, true)
            scrollChatToBottom()
           $('#message-to-send').val('')

        })
    })
}

function setUpWebsocket() {
    window.Echo.private('kanban.' + window.kanbanId)
        .listen('AddedMessage', res => {
            const person = window.people.find(f => f.id === res.userId)
            if(person.isCurrentUser) {
                return
            }
            
            addRenderMessage(res.content, person, dateToString(new Date(Date.parse(res.created_at))), false)
            scrollChatToBottom()
        })
}

function addRenderMessage(content, user, dateStr, isCurrentUser) {
    const messageHtml = document.createElement('li')
    messageHtml.classList.add('clearfix')
    messageHtml.innerHTML =   `
        <div class="message ${isCurrentUser ? 'my-message' : 'other-message pull-right' }">
            <div class="message-data">
                <img class="rounded-circle chat-user-img img-30 mr-2" style="vertical-align: bottom; height: 30px;" 
                    src="${window.location.protocol}//${window.location.hostname}/${user?.path_image ? '/avatars/' + user.path_image : '/assets/images/dashboard/1.png'}"/>
                <strong style="margin-right: 10px"> ${user?.name ?? 'John doe'} </strong>
                <i class="message-data-time"> 
                    ${dateStr}
                </i>
            </div>
            ${content}
        </div>
    `
    document.getElementById('message-container').appendChild(messageHtml)
}

function scrollChatToBottom() {
    const scrollContainer = document.getElementById('scroll-container')
    scrollContainer.scrollTop = scrollContainer.scrollHeight
}

function dateToString(dateObj) {
    return  dateObj.toLocaleDateString('en-GB', { day: "numeric", month: 'short', year: 'numeric',  }) +
        ' ' + dateObj.toLocaleTimeString('en-GB')
}