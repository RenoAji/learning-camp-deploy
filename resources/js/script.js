const addSectionButton = document.querySelector('[data-action="add-section"]');
const form = document.getElementById('courseForm');
addSectionButton.onclick = addSection;

if (window.location.pathname.split("/")[window.location.pathname.split("/").length-1] == 'edit') {
    document.querySelectorAll('[data-action="delete-section"]').forEach(function(button){
        button.onclick = function(e){
            removeSection(e);
        }
    });

    document.querySelectorAll('[data-action="delete-quiz"]').forEach(function(button){
        button.onclick = function(e){
            removeQuiz(e);
        }
    });

    document.querySelectorAll('[data-action="delete-question"]').forEach(function(button){
        button.onclick = function(e){
            removeQuestion(e);
        }
    });

    document.querySelectorAll('[data-action="delete-answer"]').forEach(function(button){
        button.onclick = function(e){
            removeAnswer(e);
        }
    });


    document.querySelectorAll('[data-action="add-quiz"]').forEach(function(button){
        button.onclick = function(e){
            addQuiz(e);
        }
    });

    document.querySelectorAll('[data-action="add-question"]').forEach(function(button){
        button.onclick = function(e){
            addQuestion(e);
        }
    });

    document.querySelectorAll('[data-action="add-answer"]').forEach(function(button){
        button.onclick = function(e){
            addAnswer(e);
        }
    });
}


form.addEventListener('submit', function(e){
    e.preventDefault();
    fetch(form.action, {
        method: "post",
        body: new FormData(form, e.submitter)

    })
    .then(response => response.json())
    .then(data => {
        if(data.redirect){
            window.location.replace(data.redirect);
        }else{
            const alert = document.createElement('div');
            alert.role = 'alert';
            alert.classList.add('alert', 'alert-error');
            alert.innerHTML = ` <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>${data.message}</span>`;
            if(document.querySelector('[role="alert"]')){
                document.querySelector('[role="alert"]').replaceWith(alert);
            }else{
                document.body.insertBefore(alert,document.body.firstElementChild);
            }
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});



function addSection(){
    const index = document.querySelectorAll('[data-element="section"]').length;
    const createSection = document.createElement('div');
    createSection.classList.add('border-b-2', 'border-slate-500');
    createSection.setAttribute('data-element', 'section');
    createSection.innerHTML =  `
                                <h1 class="text-xl flex content-center gap-2">
                                    Section ${index+1}
                                    <div class="tooltip" data-tip="Delete Section">
                                        <button class="btn btn-square btn-error mt-1 btn-xs" data-action="delete-section" type="button" >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>  
                                    </div>
                                </h1>
                                <label class="input input-bordered flex items-center gap-2">
                                    Section Title:
                                    <input type="text" class="grow" placeholder="Title" name="sections[${index}][title]" required />
                                </label>
                                <label class="input flex items-center">Section Content</label>
                                <input id="section-${index}-content" type="hidden" name="sections[${index}][content]" required >
                                <trix-editor input="section-${index}-content"></trix-editor>
                                <button class="btn btn-active btn-neutral mt-1" data-action="add-quiz" type="button">
                                    Add Quiz to this section
                                </button>`
    createSection.querySelector('[data-action="delete-section"]').onclick = function(e){
        removeSection(e);
    };

    createSection.querySelector('[data-action="add-quiz"]').onclick = function(e){
        addQuiz(e);
    };
    form.insertBefore(createSection, addSectionButton);
}

function addQuiz(e){
    const sectionElement = e.currentTarget.closest('[data-element="section"]');
    const section = Array.prototype.indexOf.call(document.querySelectorAll('[data-element="section"]'),sectionElement); //Section Index
    const createQuiz = document.createElement('div');
    createQuiz.classList.add('border', 'border-slate-400', 'ml-3', 'm-2', 'space-y-2', 'p-2');
    createQuiz.setAttribute('data-element', 'quiz');
    createQuiz.innerHTML = `<h1 class="text-xl mb-2">
                                Quiz
                                <div class="tooltip" data-tip="Delete Quiz">
                                    <button type="button" class="btn btn-square btn-error mt-1 btn-xs" data-action="delete-quiz">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>  
                                </div>
                            </h1>
                            <label class="input input-bordered flex items-center gap-2">
                                Minimum Grade:
                                <input type="number" class="grow" placeholder="minimum grade for student to pass" name="sections[${section}][minimum_grade]" min=0 max=100 required />
                            </label>
                            <p>Check the radio button to set the correct answer</p>

                            <button class="btn btn-active btn-neutral mt-1" type="button" data-action="add-question">
                                Add Question
                            </button>`;
    createQuiz.querySelector('[data-action="add-question"]').onclick = function(e){
        addQuestion(e); 
    };

    createQuiz.querySelector('[data-action="delete-quiz"]').onclick = function(e){
        removeQuiz(e);
    };

    e.currentTarget.parentElement.insertBefore(createQuiz, e.currentTarget);
    e.currentTarget.style.display = "none";
    createQuiz.querySelector('[data-action="add-question"]').click();
}

function addQuestion(e){
    const sectionElement = e.currentTarget.closest('[data-element="section"]');
    const section = Array.prototype.indexOf.call(document.querySelectorAll('[data-element="section"]'),sectionElement);
    const index = sectionElement.querySelectorAll('[data-element="question"]').length;
    const createQuestion = document.createElement('div');

    createQuestion.setAttribute('data-element', 'question');
    createQuestion.innerHTML =` <h1 class="text-l mb-2">
                                    Question ${index+1}
                                    <div class="tooltip" data-tip="Delete Question">
                                        <button type="button" class="btn btn-square btn-error mt-1 btn-xs" data-action="delete-question">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>  
                                    </div>
                                </h1>
                                <label class="input input-bordered flex items-center gap-2">
                                    Question ${index+1}:
                                    <input type="text" class="grow" placeholder="Title" name="sections[${section}][questions][${index}][question]" required />
                                </label>
                                <button class="btn btn-active btn-neutral mt-1" type="button" data-action="add-answer" \>
                                    Add Answer
                                </button>`;
    createQuestion.classList.add('border-b', 'border-black', 'p-2');

    createQuestion.querySelector('[data-action="delete-question"]').onclick = function(e){
        removeQuestion(e);
    };
    createQuestion.querySelector('[data-action="add-answer"]').onclick = function(e){
        addAnswer(e);
    };
    e.target.parentNode.insertBefore(createQuestion,e.target);
    createQuestion.querySelector('[data-action="add-answer"]').click();
    createQuestion.querySelector('[data-action="add-answer"]').click();
}

function addAnswer(e) {
    const questionElement = e.currentTarget.closest('[data-element="question"]');//element question dimana answer ini berada
    const sectionElement = e.currentTarget.closest('[data-element="section"]');
    const index = questionElement.querySelectorAll('[data-element="answer"]').length;
    const section = Array.prototype.indexOf.call(document.querySelectorAll('[data-element="section"]'),sectionElement);
    const question = Array.prototype.indexOf.call(sectionElement.querySelectorAll('[data-element="question"]'),questionElement);
    const createAnswer = document.createElement('label');

    createAnswer.setAttribute('data-element', 'answer');
    createAnswer.classList.add('input', 'input-bordered', 'flex', 'items-center', 'gap-2')
    createAnswer.innerHTML = `  Answer ${index+1}:
                                <input type="text" class="grow" placeholder="Title" name="sections[${section}][questions][${question}][answers][${index}][text]" required/>
                                <input type="radio" name="sections[${section}][questions][${question}][correct]" class="radio" value=${index} />
                                <div class="tooltip" data-tip="Delete Answer">
                                    <button type="button" class="btn btn-square btn-error mt-1 btn-xs" data-action="delete-answer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>  
                                </div>`
    createAnswer.querySelector('[type="radio"]').checked = index==0; //if index = 0 it's checked
    e.target.parentNode.insertBefore(createAnswer,e.target);
    createAnswer.querySelector('[data-action="delete-answer"]').onclick = function(e){
        removeAnswer(e);
    };
}

function removeSection(e){
    const sectionElement = e.currentTarget.closest('[data-element="section"]');
    const sectionIndex = Array.prototype.indexOf.call(document.querySelectorAll('[data-element="section"]'),sectionElement);
    sectionElement.remove();
    const sectionsNodeList = document.querySelectorAll('[data-element="section"]');

    for (let index = sectionIndex; index < sectionsNodeList.length; index++) {
        const section = sectionsNodeList[index];
        const headerText = section.firstElementChild.firstChild;
        const inputTitle = section.querySelector(`[name="sections[${index+1}][title]"`);
        const inputContent = section.querySelector(`[name="sections[${index+1}][content]"`);
        const inputContentTrix = section.querySelector('trix-editor');

        headerText.replaceWith(document.createTextNode('Section ' + (index+1)));
        inputTitle.setAttribute('name',`sections[${index}][title]`);
        inputContent.setAttribute('name',`sections[${index}][content]`);
        inputContent.setAttribute('id',`section-${index}-content`);
        inputContentTrix.setAttribute('input',`section-${index}-content`); 

        section.querySelectorAll('[data-element="question"').forEach(function(question,j){
            const label = question.querySelector('label');
            const inputQuestion = label.querySelector('input');

            inputQuestion.setAttribute('name',`sections[${index}][questions][${j}][question]`);

            question.querySelectorAll('[data-element="answer"]').forEach(function(answer,k){
                const inputAnswer = answer.firstElementChild;
                const inputCorrect = answer.children[1];

                inputAnswer.setAttribute('name', `sections[${index}][questions][${j}][answers][${k}]`);
                inputCorrect.setAttribute('name', `sections[${index}][questions][${j}][correct]`);
            });
        });
    }
}

function removeQuiz(e){
    e.currentTarget.closest('[data-element="quiz"]').parentElement.querySelector('[data-action="add-quiz"]').style.display="initial";
    e.currentTarget.closest('[data-element="quiz"]').remove();
}

function removeQuestion(e){
    const questionElement = e.currentTarget.closest('[data-element="question"]'); //deleted quetion element
    const sectionElement = questionElement.closest('[data-element="section"]');
    const sectionIndex = Array.prototype.indexOf.call(document.querySelectorAll('[data-element="section"]'),sectionElement);
    const questionIndex = Array.prototype.indexOf.call(sectionElement.querySelectorAll('[data-element="question"]'),questionElement); //deleted question index
    questionElement.remove();
    const questionsNodeList = sectionElement.querySelectorAll('[data-element="question"]');

    for(let index = questionIndex; index < questionsNodeList.length; index++) {
        const question = questionsNodeList[index];
        const headerText = question.firstElementChild.firstChild;
        const label = question.querySelector('label');
        const labelText = label.firstChild;
        const inputQuestion = label.querySelector('input');

        console.log(index);
        headerText.replaceWith(document.createTextNode('Question ' + (index+1)));
        labelText.replaceWith(document.createTextNode('Question ' + (index+1)));
        inputQuestion.setAttribute('name',`sections[${sectionIndex}][questions][${index}][question]`);

        question.querySelectorAll('[data-element="answer"]').forEach(function(answer,answerIndex){
            const inputAnswer = answer.firstElementChild;
            const inputCorrect = answer.children[1];

            inputAnswer.setAttribute('name', `sections[${sectionIndex}][questions][${index}][answers][${answerIndex}]`);
            inputCorrect.setAttribute('name', `sections[${sectionIndex}][questions][${index}][correct]`);
        });
    }
}

function removeAnswer(e){
    const questionElement = e.currentTarget.closest('[data-element="question"]'); //elemen question pembungkus answer yg dihapus
    const sectionElement = e.currentTarget.closest('[data-element="section"]');
    const questionIndex = Array.prototype.indexOf.call(sectionElement.querySelectorAll('[data-element="question"]'),questionElement);
    const sectionIndex = Array.prototype.indexOf.call(document.querySelectorAll('[data-element="section"]'),sectionElement);
    const answerElement = e.currentTarget.closest('[data-element="answer"]'); //deleted answer 
    const answerIndex = Array.prototype.indexOf.call(questionElement.querySelectorAll('[data-element="answer"]'), answerElement);
    answerElement.remove();
    const answersNodeList = questionElement.querySelectorAll('[data-element="answer"]');

    for (let index = answerIndex; index < answersNodeList.length; index++) {
        const answer = answersNodeList[index];
        const labelText = answer.firstChild;
        const inputAnswer = answer.firstElementChild;
        const inputCorrect = answer.children[1];

        labelText.replaceWith(document.createTextNode('Answer ' + (index+1) + ':'));
        inputAnswer.setAttribute('name', `sections[${sectionIndex}][questions][${questionIndex}][answers][${answerIndex}]`);
        inputCorrect.setAttribute('name', `sections[${sectionIndex}][questions][${questionIndex}][correct]`);

        
    }
}




//TRIX EDITOR CONFIGURATION
document.addEventListener("trix-attachment-add", function(event) {
    if (event.attachment.file) {
        uploadFileAttachment(event.attachment)
    }
})

function uploadFileAttachment(attachment) {
    uploadFile(attachment.file, setProgress, setAttributes)

    function setProgress(progress) {
        attachment.setUploadProgress(progress)
    }

    function setAttributes(attributes) {
        attachment.setAttributes(attributes)
    }
}

function uploadFile(file, progressCallback, successCallback) {
    var formData = new FormData()
    formData.append("file", file)

    var xhr = new XMLHttpRequest()
    xhr.open("post", "/attachment", true)
    const csrf_token = document.getElementsByName('csrf-token')[0].content;
    xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token)

    xhr.upload.addEventListener("progress", function(event) {
        var progress = event.loaded / event.total * 100
        progressCallback(progress)
    })

    xhr.addEventListener("load", function(event) {
        if (xhr.status == 200) {
            var response = xhr.responseText;
            var attributes = {
                url: response,
                href: response + "/?content-disposition=attachment"
            }
            successCallback(attributes)
        }
    })

    xhr.send(formData)
}