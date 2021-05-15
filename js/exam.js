let urlParams = new URLSearchParams(window.location.search);
let id_exam = urlParams.get('id');

if(!id_exam) {
    window.location.href = "404.html";
}

let id_user = sessionStorage.getItem('id_user');

checkOwner();
generateTable();

function checkOwner() {
    let req = server + "ExamController.php?ep=getAllExamsForCreator&id_creator=" + id_user;
    $.get(req, function(resp){
        let exams = resp.tests;
        let cnt = 0;
        exams.forEach(exam => {
            if(exam.id == id_exam) {
                cnt++;
                document.getElementById('exam-name').innerHTML=exam.name;
                document.getElementById('exam-start').innerHTML=exam.start;
                document.getElementById('exam-end').innerHTML=exam.end;
            }
        });

        if(cnt < 1) {
            window.location.href = "404.html";
        }
    });
}

function generateTable() {
    let req = server + "ExamController.php?ep=getExamsStudents&id_exam=" + id_exam;

    let writing = document.getElementById('table-writing');
    let finished = document.getElementById('table-finished');

    $.get(req, function(resp){
        if(resp['status'] == 'OK') {
            
            let students = resp['tests'];

            students.forEach(student => {
                if(student.status == "open") {
                    if(document.getElementById('writing-none')) {
                        document.getElementById('writing-none').remove();
                    }
                    var row = writing.insertRow();
                }
                else {
                    if(document.getElementById('finished-none')) {
                        document.getElementById('finished-none').remove();
                    }
                    var row = finished.insertRow();
                }

                row.insertCell(0).innerHTML = student.id;
                row.insertCell(1).innerHTML = student.name;
                row.insertCell(2).innerHTML = student.surname;                

                if(student.status == "closed") {
                    row.insertCell(3).innerHTML = student.score;
                    let cell = row.insertCell(4).innerHTML = `
                    <a href="evaluate.html?id_exam=${id_exam}&id_student=${student.id}" class="btn btn-sm btn-dark rounded-pill" data-toggle="tooltip" data-placement="top" title="Ohodnotiť">
                        <div class="material-icons align-middle fs-5">history_edu</div>
                    </a> 
                    `;
                }
                else {
                    row.insertCell(3).innerHTML = `<div class="text-center">-</div>`;
                    let cell = row.insertCell(4).innerHTML = `<div class="text-center">-</div>`;
                }
                
            });
        }
        else {
            
        }
    setTooltips();
    });
}

// EXPORT BUTTONS
function exportPdf() {
    $.get(server + 'ExportController.php?ep=exportPDF&id_test=' + id_exam, function(resp){
        if(resp['status'] == 'OK') {
            downloadZip(server + resp['path']);
            $.get(server + 'ExportController.php?ep=deleteZip&filename=' + resp['path'], function(respon){
            });
        }
        else {
            alert(resp['message']);
        }
    });
}

function exportCsv() {
    $.get(server + 'ExportController.php?ep=exportCSV&id_test=' + id_exam, function(resp){
        if(resp['status'] == 'OK') {
            downloadURI(server + resp['message']);
            $.get(server + 'ExportController.php?ep=deleteCsv&filename=' + resp['message'], function(respon){
            });
        }else {
            alert(resp['message']);
        }
    });
}

function downloadURI(uri) {
    var link = document.createElement('a');
    link.download = 'export.csv';
    link.href = uri;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    delete link;
 }
 function downloadZip(uri) {
    var link = document.createElement('a');
    link.download = 'exams.zip';
    link.href = uri;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    delete link;
 }
// SET TOOLTIPS
function setTooltips() {
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
}