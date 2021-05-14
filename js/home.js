let id_user = sessionStorage.getItem('id_user');

generateTable();

function generateTable() {    
    let req = server + "ExamController.php?ep=getAllExamsForCreator&id_creator=" + id_user;

    let table = document.getElementById('table-body');
    
    $.get(req, function(resp) {
        console.log(resp);
        if(resp['status'] == 'OK') {
            
            let cnt = 0;
            let tests = resp['tests'];            
            tests.forEach(test => {
                cnt++;
                let row = table.insertRow();
                row.insertCell(0).innerHTML = test.name;
                row.insertCell(1).innerHTML = test.code;
                row.insertCell(2).innerHTML = test.start;
                row.insertCell(3).innerHTML = test.end;
                let statusCell = row.insertCell(4);
                statusCell.innerHTML = test.status;
                statusCell.setAttribute('id','status-' + cnt);
                if(test.status == "active") {
                    let actionCell = row.insertCell(5);
                    actionCell.setAttribute('id','action-' + cnt);             
                    actionCell.innerHTML = `
                    <a href="exam.html?id=${test.id}" class="btn btn-sm btn-dark rounded-pill d-inline-block mx-1" data-toggle="tooltip" data-placement="top" title="Detail testu">
                        <div class="material-icons align-middle fs-5">visibility</div>
                    </a>
                    <button id="btn-${cnt}" onclick="changeStatus(${cnt},${test.id})" class="btn btn-sm btn-dark rounded-pill d-inline-block mx-1" data-toggle="tooltip" data-placement="top" title="Deaktivovať">
                        <div id="btn-div-${cnt}" class="material-icons align-middle fs-5">toggle_on</div>
                    </button>
                    <a href="notifications.html?id=${test.id}" class="btn btn-sm btn-dark rounded-pill d-inline-block mx-1" data-toggle="tooltip" data-placement="top" title="Upozornenia">
                        <div class="material-icons align-middle fs-5">notifications</div>
                    </a>
                `;
                }
                else {
                    let actionCell = row.insertCell(5);                    
                    actionCell.setAttribute('id','action-' + cnt);             
                    actionCell.innerHTML = `
                    <a href="exam.html?id=${test.id}" class="btn btn-sm btn-dark rounded-pill d-inline-block mx-1" data-toggle="tooltip" data-placement="top" title="Detail testu">
                        <div class="material-icons align-middle fs-5">visibility</div>
                    </a>
                    <button  onclick="changeStatus(${cnt},${test.id})" class="btn btn-sm btn-dark rounded-pill d-inline-block mx-1" data-toggle="tooltip" data-placement="top" title="Aktivovať">
                        <div id="btn-div-${cnt}" class="material-icons align-middle fs-5">toggle_off</div>
                    </button>
                    <a href="notifications.html?id=${test.id}" class="btn btn-sm btn-dark rounded-pill d-inline-block mx-1" data-toggle="tooltip" data-placement="top" title="Upozornenia">
                        <div class="material-icons align-middle fs-5">notifications</div>
                    </a>
                `;
                }                    
            });
            
        }
        else {
            table.innerHTML = 
            `<tr>
            <td class="text-center" colspan="6">Žiadne testy</td>
            </tr>`;
        }
        setTooltips();
    });
}


// ACTIVATE / DEACTIVATE EXAM     
function changeStatus(cnt,id) {
    data = {};
    data.id_exam = id;
    $.ajax(
        {
        url: server+'ExamController.php?ep=changeExamsStatus',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function(resp){
            if(resp['status'] == 'OK'){
                updateRow(cnt, resp.newStatus);
            }else{
                alert(resp.message);
            }
        }
    });         
}

function updateRow(cnt, status) {
    let statusCell = document.getElementById('status-' + cnt);
    statusCell.innerHTML = status;
    let div = document.getElementById('btn-div-' + cnt);
    div.innerHTML = status == 'active' ? 'toggle_on' : 'toggle_off';
    return null;
}

// SET TOOLTIPS
function setTooltips() {
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
}