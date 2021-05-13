const getExams = async () => {
    const response = await fetch('js/config.json');
    const json = await response.json();
    console.log(json);

    const server = json.url;

    console.log(server);

    let id_user = 1;
    // let id_user = sessionStorage.getItem('id_user');
    
    let req = server + "ExamController.php?ep=getAllExamsForCreator&id_creator=" + id_user;

    console.log(req);

    let table = document.getElementById('table-body');

    $.get(req, function(resp){
        console.log(resp);
        if(resp['status'] == 'OK'){
            
            let tests = resp['tests'];

            console.log(tests);

            if(tests.length === 0) {
                table.innerHTML = 
                `<tr>
                    <td class="text-center" colspan="6">Žiadne testy</td>
                </tr>`;
            }
            else {
                tests.forEach(test => {
                    let row = table.insertRow();
                    row.insertCell(0).innerHTML = test.description;
                    row.insertCell(1).innerHTML = test.code;
                    row.insertCell(2).innerHTML = test.start;
                    row.insertCell(3).innerHTML = test.end;
                    row.insertCell(4).innerHTML = test.status;
                    let cell = row.insertCell(5).innerHTML = `
                        <a href="exam.html?id=${test.id}" class="btn btn-sm btn-dark rounded-pill d-inline-block mx-1" data-toggle="tooltip" data-placement="top" title="Detail testu">
                            <div class="material-icons align-middle fs-5">visibility</div>
                        </a>
                    `;
                    cell.class = "text-center";
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

getExams();