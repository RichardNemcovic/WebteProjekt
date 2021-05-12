getExamDetail(event);

function getExamDetail(event){
    if(event){
        event.preventDefault();
    }

    let urlParams = new URLSearchParams(window.location.search);
    let id_exam = urlParams.get('id');

    if(!id_exam) {
        window.location.href = "404.html";
    }

    let req = "http://147.175.98.107/zaver/ExamController.php?ep=getExamsStudents&id=" + id_exam;

    let writing = document.getElementById('table-writing');
    let finished = document.getElementById('table-finished');

    $.get(req, function(resp){
        if(resp['status'] == 'OK') {
            
            let students = resp['students'];

            students.forEach(student => {
                if(student.status == "writing") {
                    var row = writing.insertRow();
                }
                else {
                    var row = finished.insertRow();
                }

                row.insertCell(0).innerHTML = student.id;
                row.insertCell(1).innerHTML = student.name;
                row.insertCell(2).innerHTML = student.surname;
                row.insertCell(3).innerHTML = student.status;
                row.insertCell(4).innerHTML = student.score;
                let cell = row.insertCell(5).innerHTML = `
                <a href="evaluate.html?id_exam=${id_exam}&id_student=${student.id}" class="btn btn-sm btn-dark rounded-pill" data-toggle="tooltip" data-placement="top" title="Ohodnotiť">
                    <div class="material-icons align-middle fs-5">history_edu</div>
                </a> 
                `;
                cell.class = "text-center";
            });
        }
        else {
            
        }
    });
}