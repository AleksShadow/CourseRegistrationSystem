console.log("Script is running...");

// event listeners for all checkboxes for courses
const checkboxes = document.querySelectorAll("input[name='courseId[]']");
console.log(checkboxes);
for (var checkbox of checkboxes) {
    checkbox.addEventListener("change", (e) => {
        if (e.target.checked) {
            console.log("Checkbox is checked..");
            let errorMessage = $('#errorMsg').text();
            if (errorMessage != "Your selection exeed the max weekly hours") {
                $('#errorMsg').text("");
            }
  
        } else {
            let errorMessage = $('#errorMsg').text();
            if (errorMessage == "Your selection exeed the max weekly hours") {
                $('#errorMsg').text("");
            }
            console.log("Checkbox is not checked..");
        }
    });
}


$(function() {
    $("#Semester").on('change', function() {
        let formStr = '<form action="CourseSelection.php">';
        formStr += '<input type="hidden" name="SemesterSelected" value="';
        formStr += this.value;
        formStr += '"></form>';
        $(formStr).appendTo('body').submit();
    });
});
