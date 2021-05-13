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
            }
        });

        if(cnt < 1) {
            window.location.href = "404.html";
        }
    });
}

function generateTable() {    
    let req = server + "ExamController.php?ep=getExamById&id_exam=" + id_exam + "&id_user=" + id_student; 
    
    // GET REQUEST BODY
    $.get(req, function(resp){
        console.log(resp);

        if(resp['status'] == 'OK') {
            document.getElementById('exam-student').innerHTML = resp.studentName; // Vypis mena studenta

            let examContent = document.getElementById('exam-content');

            let cnt = 0;

            // EQUATION --------------------------------------------------------------------------------------------------------------------------------------------------
            let qEquation = resp.qEquation;

            qEquation.forEach(q => {
                cnt++;
                let div = document.createElement('div');
                let html = 
                `
                <div class="card row m-2 py-3 px-5 shadow">
                    <div class="col">
                        <div class="row">                                
                            <h6> 
                                <span class="question-number">${cnt}.</span>
                                <span>(</span> 
                                <span class="question-type">Matematický vzorec</span> 
                                <span>)</span>
                            </h6>                                                
                        </div>
                        <div class="row">                                    
                            <div class="col-md-6">
                                <p class="light-coral-txt mb-1">Otázka:</p>
                                <h6>${q.question.description}</h6>
                            </div>
                            <div class="col-md-6">
                                <p class="light-coral-txt mb-1">Odpoveď:</p>
                                <h6>${q.answer.answer}</h6>
                            </div> 
                            <hr class="mt-3">
                            <div class="col-md-3">
                                <label for="score-${cnt}" class="form-label">Počet bodov |<small> max: ${q.question.score}</small> </label>
                                <input id="score-${cnt}" type="number" min="0" class="form-control">
                            </div>
                            <div class="col-md-1">
                                <button onclick="updateAnswer(${cnt},${q.answer.id})" class="btn my-4 btn-dark btn-pill shadow rounded-pill mb-0" type="button"
                                data-toggle="tooltip" data-placement="top" title="Zmeniť hodnotenie">
                                    <span class="material-icons">edit</span>
                                </button>
                            </div>
                            <div class="col-md-4">
                                <div hidden id="danger-${cnt}" class="alert"><p class="mx-3 alert alert-danger text-center">Prosím zadajte platný počet bodov</p> </div>                                                        
                                <div hidden id="success-${cnt}" class="alert"><p class="mx-3 alert alert-success text-center">Body boli priradené <span class="material-icons align-middle">check</span> </p> </div>                                                          
                            </div>                         
                        </div>                                
                    </div>
                </div>
                `;

                div.innerHTML = html;
                examContent.appendChild(div);                                        
            });
            
            // treba posielat vsetky potrebne atributy aj ked neexistuju nastavit null alebo prazdny string
            // dostavam odazku bez odpovede
            // SKIPPING             
            // IMAGE --------------------------------------------------------------------------------------------------------------------------------------------------                            
            // let qImage = resp.qImage;

            // qImage.forEach(q => {
            //     cnt++;
            //     let div = document.createElement('div');
            //     let html = 
            //     `
            //     <div class="card row m-2 py-3 px-5 shadow">
            //         <div class="col">
            //             <div class="row">                                
            //                 <h6> 
            //                     <span class="question-number">${cnt}.</span>
            //                     <span>(</span> 
            //                     <span class="question-type">Kreslenie obrázku</span> 
            //                     <span>)</span>
            //                 </h6>                                                
            //             </div>
            //             <div class="row">                                    
            //                 <div class="col-md-6">
            //                     <p class="light-coral-txt mb-1">Otázka:</p>
            //                     <h6>${q.question.description}</h6>
            //                 </div>
            //                 <div class="col-md-6">
            //                     <p class="light-coral-txt mb-1">Odpoveď:</p>
            //                     <h6>${q.answer.answer}</h6>
            //                 </div> 
            //                 <hr class="mt-3">
            //                 <div class="col-md-3">
            //                     <label for="score-${cnt}" class="form-label">Počet bodov |<small> max: ${q.question.score}</small> </label>
            //                     <input id="score-${cnt}" type="number" min="0" class="form-control">
            //                 </div>
            //                 <div class="col-md-1">
            //                     <button onclick="updateAnswer(${cnt},${q.answer.id})" class="btn my-4 btn-dark btn-pill shadow rounded-pill mb-0" type="button"
            //                     data-toggle="tooltip" data-placement="top" title="Zmeniť hodnotenie">
            //                         <span class="material-icons">edit</span>
            //                     </button>
            //                 </div>
            //                 <div class="col-md-4">
            //                     <div hidden id="danger-${cnt}" class="alert"><p class="mx-3 alert alert-danger text-center">Prosím zadajte platný počet bodov</p> </div>                                                        
            //                     <div hidden id="success-${cnt}" class="alert"><p class="mx-3 alert alert-success text-center">Body boli priradené <span class="material-icons align-middle">check</span> </p> </div>                                                          
            //                 </div>                         
            //             </div>                                
            //         </div>
            //     </div>
            //     `;
            //     div.innerHTML = html;
            //     examContent.appendChild(div);                                        
            // });

            // PAIRING --------------------------------------------------------------------------------------------------------------------------------------------------
            let qPairs = resp.qPairs;

            qPairs.forEach(q => {
                cnt++;
                let div = document.createElement('div');
                let html1 = 
                `
                <div class="card row m-2 py-3 px-5 shadow">
                    <div class="col">
                        <div class="row">
                            <h6> 
                                <span class="question-number">${cnt}.</span>
                                <span>(</span> 
                                <span class="question-type">Párovacia otázka</span> 
                                <span>)</span>
                            </h6>                                       
                        </div>
                        <div class="row"> 
                            <div class="col">
                                <div class="col mb-3">
                                    <p class="light-coral-txt mb-1">Otázka:</p>
                                    <h6>${q.question.description}</h6>
                                </div>
                                <div class="row">
                                    <div class="col-6"><h6>Ľavý pár</h6></div>
                                    <div class="col-6"><h6>Pravý pár</h6></div>
                                </div>
                `;     
                
                let html2 = '';
                q.answer.answers.forEach(el => {
                    let row = 
                    `
                    <div class="row">
                        <div class="col-6">
                            <div class="pair-tile">${el.left}</div>
                        </div>
                        <div class="col-6">
                            <div class="pair-tile">${el.right}</div>
                        </div>
                    </div>  
                    `;
                    html2 += row;
                });

                let html3 = 
                `           
                        </div>
                            <hr class="mt-3">
                            <div class="col-md-3">
                                <label for="score-${cnt}" class="form-label">Počet bodov |<small> max: ${q.question.score}</small> </label>
                                <input id="score-${cnt}" type="number" min="0" class="form-control">
                            </div>
                            <div class="col-md-1">
                                <button onclick="updateAnswer(${cnt},${q.answer.id})" class="btn my-4 btn-dark btn-pill shadow rounded-pill mb-0" type="button"
                                data-toggle="tooltip" data-placement="top" title="Zmeniť hodnotenie">
                                    <span class="material-icons">edit</span>
                                </button>
                            </div>
                            <div class="col-md-4">
                                <div hidden id="danger-${cnt}" class="alert"><p class="mx-3 alert alert-danger text-center">Prosím zadajte platný počet bodov</p> </div>                                                        
                                <div hidden id="success-${cnt}" class="alert"><p class="mx-3 alert alert-success text-center">Body boli priradené <span class="material-icons align-middle">check</span> </p> </div>                                                          
                            </div>                                                                    
                        </div>                                
                    </div>
                </div>
                `;
                                                
                div.innerHTML = html1 + html2 + html3;
                examContent.appendChild(div);
            });

            // funguje, dostavam zly index odpovede ktoru zvolil student
            // SELECT --------------------------------------------------------------------------------------------------------------------------------------------------
            // let qSelect = resp.qSelect;

            // qSelect.forEach(q => {
            //     cnt++;
            //     let div = document.createElement('div');
                
            //     let answerFull = q.question.possibilities[q.answer.answer-1].answer;
            //     console.log(answerFull);

            //     let html = 
            //     `
            //     <div class="card row m-2 py-3 px-5 shadow">
            //         <div class="col">
            //             <div class="row">                                
            //                 <h6> 
            //                     <span class="question-number">${cnt}.</span>
            //                     <span>(</span> 
            //                     <span class="question-type">Výberová otázka</span> 
            //                     <span>)</span>
            //                 </h6>                                                
            //             </div>
            //             <div class="row">                                    
            //                 <div class="col-md-6">
            //                     <p class="light-coral-txt mb-1">Otázka:</p>
            //                     <h6>${q.question.description}</h6>
            //                 </div>
            //                 <div class="col-md-6">
            //                     <p class="light-coral-txt mb-1">Odpoveď:</p>
            //                     <h6> <small>${q.answer.answer}.</small>  "${answerFull}"</h6>
            //                 </div> 
            //                 <hr class="mt-3">
            //                 <div class="col-md-3">
            //                     <label for="score-${cnt}" class="form-label">Počet bodov |<small> max: ${q.question.score}</small> </label>
            //                     <input id="score-${cnt}" type="number" min="0" value="${q.answer.score}" class="form-control">
            //                 </div>
            //                 <div class="col-md-1">
            //                     <button onclick="updateAnswer(${cnt},${q.answer.id})" class="btn my-4 btn-dark btn-pill shadow rounded-pill mb-0" type="button"
            //                     data-toggle="tooltip" data-placement="top" title="Zmeniť hodnotenie">
            //                         <span class="material-icons">edit</span>
            //                     </button>
            //                 </div>
            //                 <div class="col-md-4">
            //                     <div hidden id="danger-${cnt}" class="alert"><p class="mx-3 alert alert-danger text-center">Prosím zadajte platný počet bodov</p> </div>                                                        
            //                     <div hidden id="success-${cnt}" class="alert"><p class="mx-3 alert alert-success text-center">Body boli priradené <span class="material-icons align-middle">check</span> </p> </div>                                                          
            //                 </div>                         
            //             </div>                                
            //         </div>
            //     </div>
            //     `;                

            //     div.innerHTML = html;
            //     examContent.appendChild(div);                                        
            // });

            // SHORT --------------------------------------------------------------------------------------------------------------------------------------------------
            let qShort = resp.qShort;

            qShort.forEach(q => {
                cnt++;
                let div = document.createElement('div');
                let html = 
                `
                <div class="card row m-2 py-3 px-5 shadow">
                    <div class="col">
                        <div class="row">                                
                            <h6> 
                                <span class="question-number">${cnt}.</span>
                                <span>(</span> 
                                <span class="question-type">Otázka s krátkou odpoveďou</span> 
                                <span>)</span>
                            </h6>                                                
                        </div>
                        <div class="row">                                    
                            <div class="col-md-6">
                                <p class="light-coral-txt mb-1">Otázka:</p>
                                <h6>${q.question.description}</h6>
                            </div>
                            <div class="col-md-6">
                                <p class="light-coral-txt mb-1">Odpoveď:</p>
                                <h6>${q.answer.answer}</h6>
                            </div> 
                            <hr class="mt-3">
                            <div class="col-md-3">
                                <label for="score-${cnt}" class="form-label">Počet bodov |<small> max: ${q.question.score}</small> </label>
                                <input id="score-${cnt}" type="number" min="0" class="form-control">
                            </div>
                            <div class="col-md-1">
                                <button onclick="updateAnswer(${cnt},${q.answer.id})" class="btn my-4 btn-dark btn-pill shadow rounded-pill mb-0" type="button"
                                data-toggle="tooltip" data-placement="top" title="Zmeniť hodnotenie">
                                    <span class="material-icons">edit</span>
                                </button>
                            </div>
                            <div class="col-md-4">
                                <div hidden id="danger-${cnt}" class="alert"><p class="mx-3 alert alert-danger text-center">Prosím zadajte platný počet bodov</p> </div>                                                        
                                <div hidden id="success-${cnt}" class="alert"><p class="mx-3 alert alert-success text-center">Body boli priradené <span class="material-icons align-middle">check</span> </p> </div>                                                          
                            </div>                         
                        </div>                                
                    </div>
                </div>
                `;

                div.innerHTML = html;
                examContent.appendChild(div);                                        
            });

        }
        else {
            alert("Nastala chyba");
        }
    setTooltips();
    });
}

getExamResult();

//                          TODO prekonzultovat a spojazdnit
// UPDATE ANSWER
function updateAnswer(n,id) {
    let points = document.getElementById('score-' + n).value;
    if(points == "") {
        let danger = document.getElementById('danger-' + n);
        danger.hidden = false;
        let success = document.getElementById('success-' + n);
        success.hidden = true;
    }
    else {
        data = {};
        data.idAnswer = id;
        data.score = points;
        console.log(data);
        $.post(server+'?ep=setAnswersScore', data, function(resp){
            if(resp['status'] == 'OK'){
                console.log('okOKOKOKOKO');
            }else{
                alert("Nastala chyba");
            }
        });

        let danger = document.getElementById('danger-' + n);
        danger.hidden = true;
        let success = document.getElementById('success-' + n);
        success.hidden = false;    
    }
}

// SET BUTTON back to exam detail
let button = document.getElementById('button-back');
button.setAttribute('href','exam.html?id=' + id_exam)

// SET TOOLTIPS
function setTooltips() {
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
}