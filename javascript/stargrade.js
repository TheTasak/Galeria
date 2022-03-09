function hoverStars(obj) {
  let id = parseInt(obj.id.substr(-1));
  for(let i = 0; i <= id; i++) {
    let obj = document.getElementById("star-" + i);
    obj.classList.add("fas");
    obj.classList.remove("far");
  }
  for(let i = id+1; i < 10; i++) {
    let obj = document.getElementById("star-" + i);
    obj.classList.add("far");
    obj.classList.remove("fas");
  }
}
function sendGrade(obj) {
  let id = parseInt(obj.id.substr(-1))+1;
  document.getElementById("grade-value").value = id;
  let form = document.getElementById("grade-form");
  form.submit();
}
