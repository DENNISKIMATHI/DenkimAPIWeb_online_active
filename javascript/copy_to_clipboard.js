function myFunction(field_input) {
  /* Get the text field */
  var copyText = document.getElementById(field_input);

  /* Select the text field */
  copyText.select();

  /* Copy the text inside the text field */
  document.execCommand("Copy");

  /* Alert the copied text */
  alert("Copied the text: " + copyText.value);
}