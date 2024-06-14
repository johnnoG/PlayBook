            function profile(){
                location.reload();
            }

            function edit(){
                let arrP=document.querySelectorAll(".data");
                let ed=document.getElementById("edit");
                let save=document.getElementById("saveChanges");
                for(let i=0;i<arrP.length;i++){
                    let textB=document.createElement("input");
                    textB.type="text";
                    textB.placeholder="enter new info";
                    textB.classList.add("new_text");
                    arrP[i].innerHTML="";
                    arrP[i].appendChild(textB);
                }
                ed.style.display="none";
                save.style.display="block";
                

            }
            function saveChanges(){
                let ed=document.getElementById("edit");
                let save=document.getElementById("saveChanges");
                let newtxt=document.querySelectorAll(".new_text");
                let arrP=document.querySelectorAll(".data");
                for(let i=0;i<arrP.length;i++){
                    arrP[i].innerHTML=newtxt[i].value;

                }
                ed.style.display="block";
                save.style.display="none";

            }