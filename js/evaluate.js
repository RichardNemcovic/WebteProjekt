let urlParams = new URLSearchParams(window.location.search);
let id_exam = urlParams.get('id_exam');
let id_student = urlParams.get('id_student');

if(!id_exam || !id_student) {
    window.location.href = "404.html";
}

let server;
// let id_user = sessionStorage.getItem('id_user');
let id_user = 1;

const getExamResult = async () => {
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
                document.getElementById('exam-student'); //TODO niekde inde
            }
        });

        if(cnt < 1) {
            window.location.href = "404.html";
        }
    });
}

function generateTable() {
    let req = server + "ExamController.php?ep=getExamById&id_exam=" + id_exam + "&id_user";

    let writing = document.getElementById('table-writing');

    $.get(req, function(resp){
        if(resp['status'] == 'OK') {
            
            
        }
        else {
            
        }
    setTooltips();
    });
}

getExamResult();

// SET BUTTON back to exam detail
let button = document.getElementById('button-back');
button.setAttribute('href','exam.html?id=' + id_exam)

// SET TOOLTIPS
function setTooltips() {
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
}