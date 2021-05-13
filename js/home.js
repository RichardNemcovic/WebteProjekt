let server;
// let id_user = sessionStorage.getItem('id_user');
let id_user = 1;

const getExams = async () => {
    const response = await fetch('js/config.json');
    const json = await response.json();
    server = json.url;
    
    generateTable();
}

function generateTable() {    
    let req = server + "ExamController.php?ep=getAllExamsForCreator&id_creator=" + id_user;

    let table = document.getElementById('table-body');
    
    $.get(req, function(resp) {
        console.log(resp);
        if(resp['status'] == 'OK') {
            
            let tests = resp['tests'];            
            tests.forEach(test => {
                let row = table.insertRow();
                row.insertCell(0).innerHTML = test.name;
                row.insertCell(1).innerHTML = test.code;
                row.insertCell(2).innerHTML = test.start;
                row.insertCell(3).innerHTML = test.end;
                row.insertCell(4).innerHTML = test.status;
                if(test.status == "active") {
                    row.insertCell(5).innerHTML = `
                    <a href="exam.html?id=${test.id}" class="btn btn-sm btn-dark rounded-pill d-inline-block mx-1" data-toggle="tooltip" data-placement="top" title="Detail testu">
                        <div class="material-icons align-middle fs-5">visibility</div>
                    </a>
                    <button onclick="deactivate(${test.id})" class="btn btn-sm btn-dark rounded-pill d-inline-block mx-1" data-toggle="tooltip" data-placement="top" title="Deaktivovať">
                        <div class="material-icons align-middle fs-5">toggle_on</div>
                    </button>
                `;
                }
                else {
                    row.insertCell(5).innerHTML = `
                    <a href="exam.html?id=${test.id}" class="btn btn-sm btn-dark rounded-pill d-inline-block mx-1" data-toggle="tooltip" data-placement="top" title="Detail testu">
                        <div class="material-icons align-middle fs-5">visibility</div>
                    </a>
                    <button onclick="deactivate(${test.id})" class="btn btn-sm btn-dark rounded-pill d-inline-block mx-1" data-toggle="tooltip" data-placement="top" title="Aktivovať">
                        <div class="material-icons align-middle fs-5">toggle_off</div>
                    </button>
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


getExams();


// DEACTIVATE EXAM                      TODO
function deactivate(id) {
    return null;
}

// SET TOOLTIPS
function setTooltips() {
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
}