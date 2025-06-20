function submitApplication(){
    //Store the values of the 3 inputs
    let requestedReport = document.getElementById("input_report").value;
    let startDate = document.getElementById("fromDate").value;
    let endDate = document.getElementById("toDate").value;

    inputOk = verifySubmission(requestedReport, startDate, endDate); //Check if user has left any input bar empty, if yes then handle it
    if(!inputOk){
        return;
    }

    //Debugging
    console.log(requestedReport);
    console.log(startDate);
    console.log(endDate);

    let form = document.createElement("form"); //Create a form
    form.action = "displayReport.php";
    form.method = "post";

    let form_input_startDate = document.createElement("input"); //Store the start date from above into an input bar
    form_input_startDate.type = "date";
    form_input_startDate.name = "startDate";
    form_input_startDate.value = startDate;

    let form_input_endDate = document.createElement("input"); //Store the end date from above into an input bar
    form_input_endDate.type = "date";
    form_input_endDate.name = "endDate";
    form_input_endDate.value = endDate;

    let form_input_report = document.createElement("input"); //Store the requestd report from above into an input bar
    form_input_report.type = "text";
    form_input_report.name = "requestedReport";
    form_input_report.value = requestedReport;

    let form_submitButton = document.createElement("input"); //Create the submit button to automatically click
    form_submitButton.type = "submit";

    //Append everything
    form.appendChild(form_input_startDate);
    form.appendChild(form_input_endDate);
    form.appendChild(form_input_report);
    form.appendChild(form_submitButton);
    document.body.appendChild(form);

    form_submitButton.click(); //Submit this form automatically

}

function verifySubmission(input_report, input_startDate, input_endDate){ //This function verifies that all the input bars are filled, if not, show alert and don't proceed
    if(input_report != "expiredCNIC"){
        if(input_startDate === "" || input_endDate === ""){
            window.alert("Please fill all input fields");
            return false;
        }
        
    }
    return true;
}