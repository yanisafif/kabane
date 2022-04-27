
// Color
var colorFields = document.getElementsByClassName('color-field');
for(const colorField of colorFields) {

    createPicker(colorField);
}

function createPicker(colorField) {
    const picker = new Picker(colorField);
    picker.onChange = function(color) {
        colorField.querySelector('input').value = color.hex;
    };
}

// Invitation
window.inviteNumber = 1

window.addInvite = function() {
    let element = document.createElement('div')
    element.classList.add('small-group', 'mt-2')
    element.id = 'invite-' + window.inviteNumber

    element.innerHTML =
            `
                <input class="form-control" maxlength="50" name="invite[${window.inviteNumber}]" type="text" required />
                <img style="width: 20px; height: 20px" src="${window.location.protocol +'//'+ window.location.hostname}/assets/svg/close.svg" onclick="onDeleteInvite(${window.inviteNumber})">
            `
    document.getElementById("invite-field-container").appendChild(element)
    window.inviteNumber++
}

window.onDeleteInvite = function (id) {
    document.getElementById("invite-field-container").removeChild(document.getElementById('invite-' + id))
}


// Column 
window.colNumber = 2
window.addCol = function () {
    let element = document.createElement('div')
    element.classList.add('small-group', 'mt-1')
    element.id = 'col-' + window.colNumber

    element.innerHTML =
            `<div class="input-group">
                <span class="input-group-text"> Name</span>
                <input class="form-control" type="text" maxlength="50" name="colname[${window.colNumber}]" required="" />
            </div>
            <div class="input-group color-field">
                <span class="input-group-text">Color</span>
                <input class="form-control" type="text" maxlength="9" name="colcolor[${window.colNumber}]" required="" />
            </div>
            <img style="width: 20px; height: 20px" src="${window.location.protocol +'//'+ window.location.hostname}/assets/svg/close.svg" onclick="deleteCol(${window.colNumber})">
            `
        document.getElementById("col-fields-container").appendChild(element)
    onAddColumn(element)
    window.colNumber++
}

window.onAddColumn = function (element) {
    createPicker(element.querySelector('div.color-field'))
}

window.deleteCol = function (id) {
    document.getElementById("col-fields-container").removeChild(document.getElementById('col-' + id))
}
