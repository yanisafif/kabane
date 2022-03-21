
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
let m = 1;
const inviteContainer = document.getElementById("invite-field-container");
function addInvite() {
    let element = document.createElement('div');
    element.classList.add('small-group', 'mt-2');
    element.id = 'invite-' + m;

    element.innerHTML =
            `
                <input class="form-control" maxlength="50" name="invite[${m}]" type="text" required />
                <img style="width: 20px; height: 20px" src="${window.location.protocol +'//'+ window.location.hostname}/assets/svg/close.svg" onclick="deleteInvite(${m})">
            `
    inviteContainer.appendChild(element);
    m++;
}

function deleteInvite(id) {
    inviteContainer.removeChild(document.getElementById('invite-' + id))
}


// Column 
let n = 2;
const colContainer = document.getElementById("col-fields-container");
function addCol() {
    let element = document.createElement('div');
    element.classList.add('small-group', 'mt-1');
    element.id = 'col-' + n;

    element.innerHTML =
            `<div class="input-group">
                <span class="input-group-text"> Name</span>
                <input class="form-control" type="text" maxlength="50" name="colname[+ ${n} +]" required="" />
            </div>
            <div class="input-group color-field">
                <span class="input-group-text">Color</span>
                <input class="form-control" type="text" maxlength="9" name="colcolor['+ ${n} +']" required="" />
            </div>
            <img style="width: 20px; height: 20px" src="${window.location.protocol +'//'+ window.location.hostname}/assets/svg/close.svg" onclick="deleteCol(${n})">`
    colContainer.appendChild(element);
    onAddColumn(element)
    n++;
}

function onAddColumn(element) {
    createPicker(element.querySelector('div.color-field'));
}

function deleteCol(id) {
    colContainer.removeChild(document.getElementById('col-' + id))
}
