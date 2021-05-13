let urlParams = new URLSearchParams(window.location.search);
let id_exam = urlParams.get('id');

if(!id_exam) {
    window.location.href = "404.html";
}

let server;
// let id_user = sessionStorage.getItem('id_user');
let id_user = 1;

const getStudents = async () => {
    const response = await fetch('js/config.json');
    const json = await response.json();
    server = json.url;

    checkOwner();
    generateTable();
}

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
                row.insertCell(3).innerHTML = student.status;
                row.insertCell(4).innerHTML = student.score;

                if(student.status == "closed") {
                    let cell = row.insertCell(5).innerHTML = `
                    <a href="evaluate.html?id_exam=${id_exam}&id_student=${student.id}" class="btn btn-sm btn-dark rounded-pill" data-toggle="tooltip" data-placement="top" title="Ohodnotiť">
                        <div class="material-icons align-middle fs-5">history_edu</div>
                    </a> 
                    `;
                }
                else {
                    let cell = row.insertCell(5).innerHTML = `<div class="text-center">-</div>`;
                }
                
            });
        }
        else {
            
        }
    setTooltips();
    });
}

getStudents();


// EXPORT BUTTONS
function exportPdf() {
    let req = "http://147.175.98.107/zaver/ExamController.php?ep=exportPDF&id=" + id_exam
    $.get(req, function(resp){
        if(resp['status'] == 'OK') {
            let path = resp['path']
            //TODO download
        }
        else {
            
        }
    });
}

function exportCsv() {
    let req = "http://147.175.98.107/zaver/ExamController.php?ep=exportCSV&id=" + id_exam
    $.get(req, function(resp){
        if(resp['status'] == 'OK') {
            let path = resp['path']
            //TODO download
        }
        else {
            
        }
    });
}

// SET TOOLTIPS
function setTooltips() {
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
}