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
        if(exams) {
            exams.forEach(exam => {
                if(exam.id == id_exam) {
                    cnt++;
                    document.getElementById('exam-name').innerHTML=exam.name;
                    document.getElementById('exam-start').innerHTML=exam.start;
                    document.getElementById('exam-end').innerHTML=exam.end;
                }
            });
        }

        if(cnt < 1) {
            window.location.href = "404.html";
        }
    });
}

function generateTable() {
    let req = server + "ExamController.php?ep=cheating&id_exam=" + id_exam;
    let table = document.getElementById('table-body');

    $.get(req, function(resp){
        console.log(resp);
        if(resp['status'] == 'OK') {            
            let students = resp['cheater'];

            if(students.length == 0) {
                arrayEmpty(table);
            }
            else {
                students.forEach(student => {                
                    var row = table.insertRow();                
                    row.insertCell(0).innerHTML = student.ais_id;
                    row.insertCell(1).innerHTML = student.name;
                    row.insertCell(2).innerHTML = student.time;                                                
                });
            }
        }
        else {
            arrayEmpty(table);
        }
    setTooltips();
    });
}

function arrayEmpty(table) {
    let row = table.insertRow();                
    let cell = row.insertCell(0);
    cell.setAttribute('colspan',4);
    cell.classList.add('text-center');
    cell.innerHTML = "Žiadni študenti";
}

// SET TOOLTIPS
function setTooltips() {
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
}