var cShort = 1;
var cSelect = 1;
var cImage = 1;
var cEquation = 1;
var cPairs = 1;

var oSelect = {};
var oPair = {};

var globalID = 1;

var container = document.getElementById('question-list');

function deleteQuestion(id){
    document.getElementById(id).remove();
}

function createShort(){
    let q = document.createElement('div');
    let id = cShort;

    q.className += 'card row m-2 py-3 px-5 shadow';
    q.id = globalID;
    q.innerHTML = `
    <div class="col" id="short-`+id+`">
        <div class="row text-center">
            <h6> 
                <!--<span class="question-number">1.</span>-->
                <span>(</span> 
                <span class="question-type">Otázka s krátkou odpoveďou</span> 
                <span>)</span>
            </h6>                                                                        
        </div>
        <div class="row">                                    
            <div class="col">
                <label for="short-description-`+id+`" class="form-label">Otázka</label>
                <textarea id="short-description-`+id+`" class="form-control"></textarea>  
                <label for="short-answer-`+id+`" class="form-label">Správna odpoveď</label>
                <input id="short-answer-`+id+`" type="text" class="form-control">
            </div>
            <hr class="mt-4">
            <div class="col-md-4 text-center text-md-start">
                <label for="short-score-`+id+`" class="form-label">Počet bodov</label>
                <input id="short-score-`+id+`" type="number" min="0" class="form-control">
            </div>
            <div class="col-md-8 text-center text-md-end">
                <button class="btn mt-3 btn-sm btn-dark blue-bg btn-delete" onclick="deleteQuestion(`+globalID+`)">
                    <span class="material-icons align-middle">delete</span> Odstrániť otázku
                </button> 
            </div>                                
        </div> 
    </div>`

    cShort++;
    globalID++;
    container.appendChild(q);
}
//SELECT-------------------------------------
function addSelectOption(qID, cID){
    oSelect[qID]++;

    let l = document.createElement('label');
    l.classList.add('form-label');
    l.innerHTML = oSelect[qID];
    l.for = 'select-'+cID+'-'+oSelect[qID];
    l.id = 'selectl-'+cID+'-'+oSelect[qID];

    let i = document.createElement('input');
    i.type = 'text';
    i.classList.add('form-control');
    i.id = 'select-'+cID+'-'+oSelect[qID];

    document.getElementById('q-con-'+cID).appendChild(l);
    document.getElementById('q-con-'+cID).appendChild(i);
}

function deleteSelectOption(qID, cID){
    if(document.getElementById('select-'+cID+'-'+oSelect[qID]) && document.getElementById('selectl-'+cID+'-'+oSelect[qID]) && oSelect[qID] > 2){
        document.getElementById('select-'+cID+'-'+oSelect[qID]).remove();
        document.getElementById('selectl-'+cID+'-'+oSelect[qID]).remove();

        oSelect[qID] --;
    }
}

function createSelect(){
    let q = document.createElement('div');
    let id = cSelect;
    oSelect[globalID] = 2;

    q.className += 'card row m-2 py-3 px-5 shadow';
    q.id = globalID;
    q.innerHTML = `
    <div class="col" id="select-`+id+`">
        <div class="row text-center">
            <h6> 
                <!--<span class="question-number">2.</span>-->
                <span>(</span> 
                <span class="question-type">Výberová otázka</span> 
                <span>)</span>
            </h6>
        </div>
        <div class="row">   
            <div class="col" id="q-con-`+id+`">
                <label for="select-description-`+id+`" class="form-label">Otázka</label>
                <textarea id="select-description-`+id+`" class="form-control mb-2"></textarea> 
                <h6>Možnosti:</h6>                                                         
                <label for="select-`+id+`-1" id="selectl-`+id+`-1" class="form-label">1.</label>
                <input id="select-`+id+`-1" type="text" class="form-control">   
                <label for="select-`+id+`-2" id="selectl-`+id+`-2" class="form-label">2.</label>
                <input id="select-`+id+`-2" type="text" class="form-control">             
            </div>
            <div class="row my-3">
                <div class="col-md-6 text-center">
                    <button class="btn mt-3 light-coral-bg white-txt shadow" type="button" onclick="addSelectOption(`+globalID+`,`+id+`)">
                        <span class="material-icons align-middle">add</span> Nová možnosť
                    </button>
                </div>
                <div class="col-md-6 text-center">
                    <button class="btn mt-3 btn-sm btn-dark blue-bg btn-delete" type="button" onclick="deleteSelectOption(`+globalID+`,`+id+`)">
                        <div class="material-icons align-middle">delete</div> Odstrániť možnosť
                    </button> 
                </div>
            </div>
            <div>
                <label for="select-answer-`+id+`" class="form-label">Číslo správnej odpovede</label>
                <input id="select-answer-`+id+`" type="number" class="form-control"> 
            </div>
            <hr class="mt-4">
            <div class="col-md-4 text-md-start">
                <label for="select-score-`+id+`" class="form-label">Počet bodov</label>
                <input id="select-score-`+id+`" type="number" min="0" class="form-control">
            </div>
            <div class="col-md-8 text-center text-md-end">
                <button class="btn mt-3 btn-sm btn-dark blue-bg btn-delete" onclick="deleteQuestion(`+globalID+`)">
                    <span class="material-icons align-middle">delete</span> Odstrániť otázku
                </button> 
            </div>                         
        </div>                                
    </div>`

    cSelect++;
    globalID++;
    container.appendChild(q);
}
//PAIR --------------------------------------
function createPairOption(qID, cID){
    oPair[qID]++;

    let div = document.createElement('div');
    div.className += 'row';
    div.id = 'pair-'+cID+'-'+oPair[qID];
    div.innerHTML = `
    <div class="col-6">
        <label for="pair-l-`+cID+`-`+oPair[qID]+`" class="form-label">`+oPair[qID]+`.</label>
        <input id="pair-l-`+cID+`-`+oPair[qID]+`" type="text" class="form-control">
    </div>
    <div class="col-6">                                
        <input id="pair-r-`+cID+`-`+oPair[qID]+`" type="text" class="form-control pairing-input">   
    </div>`
    
    document.getElementById('p-con-'+cID).appendChild(div);
}

function deletePairOption(qID, cID){
    if(document.getElementById('pair-'+cID+'-'+oPair[qID]) && oPair[qID] > 2){
        document.getElementById('pair-'+cID+'-'+oPair[qID]).remove();

        oPair[qID] --;
    }
}

function createPair(){
    let q = document.createElement('div');
    let id = cPairs;
    oPair[globalID] = 2;

    q.className += 'card row m-2 py-3 px-5 shadow';
    q.id = globalID;
    q.innerHTML = `
    <div class="col" id="pair-`+id+`">
        <div class="row text-center">
            <h6> 
                <!--<span class="question-number">3.</span>-->
                <span>(</span> 
                <span class="question-type">Párová otázka</span> 
                <span>)</span>
            </h6>                                       
        </div>
        <div class="row"> 
            <div class="col" id="p-con-`+id+`">
                <div class="col">
                    <label for="pair-description-`+id+`" class="form-label">Otázka</label>
                    <textarea id="pair-description-`+id+`" class="form-control mb-2"></textarea>
                </div>
                <div class="row">
                    <div class="col-6"><h6>Ľavý pár</h6></div>
                    <div class="col-6"><h6>Pravý pár</h6></div>
                </div>
                <div class="row" id="pair-`+id+`-1">
                    <div class="col-6">
                        <label for="pair-l-`+id+`-1" class="form-label">1.</label>
                        <input id="pair-l-`+id+`-1" type="text" class="form-control">
                    </div>
                    <div class="col-6">                                
                        <input id="pair-r-`+id+`-1" type="text" class="form-control pairing-input">   
                    </div>
                </div> 
                <div class="row" id="pair-`+id+`-2">
                    <div class="col-6">
                        <label for="pair-l-`+id+`-2" class="form-label">2.</label>
                        <input id="pair-l-`+id+`-2" type="text" class="form-control">
                    </div>
                    <div class="col-6">                                
                        <input id="pair-r-`+id+`-2" type="text" class="form-control pairing-input">   
                    </div>
                </div>             
            </div>
            <div class="row my-3">
                <div class="col-md-6 text-center">
                    <button class="btn mt-3 light-coral-bg white-txt shadow" type="button" onclick="createPairOption(`+globalID+`,`+id+`)">
                        <span class="material-icons align-middle">add</span> Nová možnosť
                    </button>
                </div>
                <div class="col-md-6 text-center">
                    <button class="btn mt-3 btn-sm btn-dark blue-bg btn-delete" type="button" onclick="deletePairOption(`+globalID+`,`+id+`)">
                        <div class="material-icons align-middle">delete</div> Odstrániť možnosť
                    </button> 
                </div>
            </div>
            <hr class="mt-3">
            <div class="col-md-4 text-md-start">
                <label for="pair-score-`+id+`" class="form-label">Počet bodov</label>
                <input id="pair-score-`+id+`" type="number" min="0" class="form-control">
            </div>
            <div class="col-md-8 text-center text-md-end">
                <button class="btn mt-3 btn-sm btn-dark blue-bg btn-delete" onclick="deleteQuestion(`+globalID+`)">
                    <span class="material-icons align-middle">delete</span> Odstrániť otázku
                </button> 
            </div>                                               
        </div>                                
    </div>`

    cPairs++;
    globalID++;
    container.appendChild(q);
}
//EQUATION-----------------------------------
function createEquation(){
    let q = document.createElement('div');
    let id = cEquation;

    q.className += 'card row m-2 py-3 px-5 shadow';
    q.id = globalID;
    q.innerHTML = `
    <div class="col" id="equation-`+id+`">
        <div class="row text-center">                                
            <h6> 
                <!--<span class="question-number">5.</span>-->
                <span>(</span> 
                <span class="question-type">Matematický vzorec</span> 
                <span>)</span>
            </h6>                                                
        </div>
        <div class="row">                                    
            <div class="col">
                <label for="equation-description-`+id+`" class="form-label">Otázka</label>
                <textarea id="equation-description-`+id+`" class="form-control"></textarea> 
            </div> 
            <hr class="mt-3">
            <div class="col-md-4 text-center">
                <label for="equation-score-`+id+`" class="text-md-start">Počet bodov</label>
                <input id="equation-score-`+id+`" type="number" min="0" class="form-control">
            </div>
            <div class="col-md-8 text-center text-md-end">
                <button class="btn mt-3 btn-sm btn-dark blue-bg btn-delete" onclick="deleteQuestion(`+globalID+`)">
                    <span class="material-icons align-middle">delete</span> Odstrániť otázku
                </button> 
            </div> 
        </div>                                
    </div>`

    cEquation++;
    globalID++;
    container.appendChild(q);
}
//IMAGE--------------------------------------
function createImage(){
    let q = document.createElement('div');
    let id = cImage;

    q.className += 'card row m-2 py-3 px-5 shadow';
    q.id = globalID;
    q.innerHTML = `
    <div class="col" id="image-`+id+`">
        <div class="row text-center">
            <h6> 
                <!--<span class="question-number">4.</span>-->
                <span>(</span> 
                <span class="question-type">Kreslenie obrázku</span> 
                <span>)</span>
            </h6>                                            
        </div>
        <div class="row">                                    
            <div class="col">
                <label for="image-description-`+id+`" class="form-label">Otázka</label>
                <textarea id="image-description-`+id+`" class="form-control"></textarea>
            </div>                          
            <hr class="mt-3">
            <div class="col-md-4 text-center">
                <label for="image-score-`+id+`" class="form-label">Počet bodov</label>
                <input id="image-score-`+id+`" type="number" min="0" class="form-control">
            </div>
            <div class="col-md-8 text-center text-md-end">
                <button class="btn mt-3 btn-sm btn-dark blue-bg btn-delete" onclick="deleteQuestion(`+globalID+`)">
                    <span class="material-icons align-middle">delete</span> Odstrániť otázku
                </button> 
            </div> 
        </div>                                
    </div>`

    cImage++;
    globalID++;
    container.appendChild(q);
}

function reset(){
    document.getElementById('fail').hidden = true;
    document.getElementById('ok').hidden = false;
    document.getElementById('ok').innerHTML = 'Test bol úspešne vytvorený.';
    
    cShort = 1;
    cSelect = 1;
    cImage = 1;
    cEquation = 1;
    cPairs = 1;
    oSelect = {};
    oPair = {};
    globalID = 1;

    container.innerHTML = '';
    window.scrollTo(0,0);
}

function error(msg){
    document.getElementById('ok').hidden = true;
    document.getElementById('fail').hidden = false;
    document.getElementById('fail').innerHTML = msg;
    window.scrollTo(0,0);
}

function createTest(event){
    if(event){
        event.preventDefault();
    }

    let data = {};
    data['description'] = document.getElementById('exam-name').value;
    data['start'] = document.getElementById('start-date').value;
    data['end'] = document.getElementById('end-date').value;
    data['creator'] = sessionStorage.getItem('id_user');
    data['qShort'] = getShortData();
    data['qSelect'] = getSelectData();
    data['qImage'] = getImageData();
    data['qEquation'] = getEquationData();
    data['qPairs'] = gePairData();

    data['start'] = data['start'].replace('T', ' ') + ':00';
    data['end'] = data['end'].replace('T', ' ') + ':00';

    if(data['qShort'].length + data['qSelect'].length + data['qEquation'].length + data['qImage'].length + data['qPairs'].length > 0){
        $.ajax(
            {
            url: server+'ExamController.php?ep=createExam',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(resp){
                if(resp['status'] == 'OK'){
                    reset();
                }else{
                    error('Nastala chyba na strane servera.');
                }
            }
        });
    }else{
        error('Test musí obsahovať aspoň jednu otázku.');
    }
}

function getShortData(){
    let data = [];

    for(let i=1; i<cShort; i++){
        if(document.getElementById('short-'+i)){
            let q = {}
            q['description'] = document.getElementById('short-description-'+i).value;
            q['score'] = document.getElementById('short-score-'+i).value;
            q['answer'] = document.getElementById('short-answer-'+i).value;

            data.push(q);
        }
    }

    return data;
}

function getSelectData(){
    let data = [];

    for(let i = 1; i < cSelect; i++){
        if(document.getElementById('select-'+i)){
            let q = {};
            q['description'] = document.getElementById('select-description-'+i).value;
            q['score'] = document.getElementById('select-score-'+i).value;
            q['correctAnswer'] = document.getElementById('select-answer-'+i).value;
            q['possibilities'] = [];
            
            let j = 1;
            while(document.getElementById('select-'+i+'-'+j)){
                q['possibilities'].push(document.getElementById('select-'+i+'-'+j).value);    
                j++;
            }
            
            data.push(q);
        }
    }

    return data;
}

function gePairData(){
    let data = [];

    for(let i = 1; i < cPairs; i++){
        if(document.getElementById('pair-'+i)){
            let q = {};
            q['description'] = document.getElementById('pair-description-'+i).value;
            q['score'] = document.getElementById('pair-score-'+i).value;
            q['answers'] = [];
            
            let j = 1;
            while(document.getElementById('pair-'+i+'-'+j)){
                let a = {};
                a['left'] = document.getElementById('pair-l-'+i+'-'+j).value;
                a['right'] = document.getElementById('pair-r-'+i+'-'+j).value;    
                
                j++;
                q['answers'].push(a);
            }
            
            data.push(q);
        }
    }

    return data;
}

function getImageData(){
    let data = [];

    for(let i=1; i<cImage; i++){
        if(document.getElementById('image-'+i)){
            let q = {}
            q['description'] = document.getElementById('image-description-'+i).value;
            q['score'] = document.getElementById('image-score-'+i).value;

            data.push(q);
        }
    }

    return data;
}

function getEquationData(){
    let data = [];

    for(let i=1; i<cEquation; i++){
        if(document.getElementById('equation-'+i)){
            let q = {}
            q['description'] = document.getElementById('equation-description-'+i).value;
            q['score'] = document.getElementById('equation-score-'+i).value;

            data.push(q);
        }
    }

    return data;
}