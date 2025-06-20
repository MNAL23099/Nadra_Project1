function acceptApplication(applicationID, applicationType){

    let form = document.createElement("form"); //Create a form
    form.action = "acceptApplication.php";
    form.method = "post";

    let form_input_applicationID = document.createElement("input"); //Store the start date from above into an input bar
    form_input_applicationID.type = "number";
    form_input_applicationID.name = "applicationID";
    form_input_applicationID.value = applicationID;

    let form_input_applicationType = document.createElement("input"); //Store the end date from above into an input bar
    form_input_applicationType.type = "text";
    form_input_applicationType.name = "applicationType";
    form_input_applicationType.value = applicationType;


    let form_submitButton = document.createElement("input"); //Create the submit button to automatically click
    form_submitButton.type = "submit";

    //Append everything
    form.appendChild(form_input_applicationID);
    form.appendChild(form_input_applicationType);
    form.appendChild(form_submitButton);
    document.body.appendChild(form);

    window.alert("Application has been accepted");

    form_submitButton.click(); //Submit this form automatically
    
}

function rejectApplication(applicationID, applicationType){

    let form = document.createElement("form"); //Create a form
    form.action = "rejectApplication.php";
    form.method = "post";

    let form_input_applicationID = document.createElement("input"); //Store the start date from above into an input bar
    form_input_applicationID.type = "number";
    form_input_applicationID.name = "applicationID";
    form_input_applicationID.value = applicationID;

    let form_input_applicationType = document.createElement("input"); //Store the end date from above into an input bar
    form_input_applicationType.type = "text";
    form_input_applicationType.name = "applicationType";
    form_input_applicationType.value = applicationType;


    let form_submitButton = document.createElement("input"); //Create the submit button to automatically click
    form_submitButton.type = "submit";

    //Append everything
    form.appendChild(form_input_applicationID);
    form.appendChild(form_input_applicationType);
    form.appendChild(form_submitButton);
    document.body.appendChild(form);

    window.alert("Application has been rejected");

    form_submitButton.click(); //Submit this form automatically

}