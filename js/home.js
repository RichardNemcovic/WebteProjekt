getExams(event);

function getExams(event){
    if(event){
        event.preventDefault();
    }

    let id_user = sessionStorage.getItem('id_user');
    
    let req = "http://147.175.98.107/zaver/ExamController.php?ep=getAllExams&id=" + id_user;

    let table = document.getElementById('table-body');

    $.get(req, function(resp){
        if(resp['status'] == 'OK'){
            
            let tests = resp['tests'];

            if(tests.length === 0) {
                table.innerHTML = 
                `<tr>
                    <td class="text-center" colspan="6">Žiadne testy</td>
                </tr>`;
            }
            else {
                tests.forEach(test => {
                    let row = table.insertRow(0);
                    row.insertCell(0).innerHTML = test.description;
                    row.insertCell(1).innerHTML = test.code;
                    row.insertCell(2).innerHTML = test.start;
                    row.insertCell(3).innerHTML = test.end;
                    row.insertCell(4).innerHTML = test.status;
                    row.insertCell(5).innerHTML = `
                        <a href="exam.html?id=${test.id}" class="btn btn-sm btn-dark rounded-pill d-inline-block mx-1" data-toggle="tooltip" data-placement="top" title="Detail testu">
                            <div class="material-icons align-middle fs-5">visibility</div>
                        </a>
                    `;
                });
            }
        }else{
            table.innerHTML = 
            `<tr>
            <td class="text-center" colspan="6">Žiadne testy</td>
            </tr>`;
        }
    });
}