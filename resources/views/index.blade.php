<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Extract Images</title>
    <style type="text/css">
        #drop_zone {
          border: 2px solid DodgerBlue;
          width:  600px;
          height: 200px;
          background: lightblue;
        }

        .button {
          background-color: DodgerBlue; /* Dodger Blue */
          border: none;
          color: white;
          padding: 15px 32px;
          text-align: center;
          text-decoration: none;
          display: inline-block;
          font-size: 16px;
        }

        .loader {
          border: 16px solid #f3f3f3; /* Light grey */
          border-top: 16px solid #3498db; /* Blue */
          border-radius: 50%;
          width: 120px;
          height: 120px;
          animation: spin 2s linear infinite;
        }

        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }

        #loading{
            display: none;
        }
    </style>
</head>
<body style="text-align: center;">
    <div id="drop_zone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" style="text-align: center;padding: 70px 0;">
      <p id="drop_message">Drag document file to this Drop Zone ...</p>
    </div>
    <br>
    <div id="loading" class="loader"></div>
    <button id="sendFile" class="button" disabled>
        Import pictures
    </button>

    <script type="text/javascript">
        function dropHandler(ev) {
            var file_name = "";
          console.log('File(s) dropped');
          document.getElementById("drop_message").innerHTML = 'File dropped';

          // Prevent default behavior (Prevent file from being opened)
          ev.preventDefault();

          if (ev.dataTransfer.items) {
            // Use DataTransferItemList interface to access the file(s)
            for (var i = 0; i < ev.dataTransfer.items.length; i++) {
              // If dropped items aren't files, reject them
              if (ev.dataTransfer.items[i].kind === 'file') {
                var file = ev.dataTransfer.items[i].getAsFile();
                console.log('... file[' + i + '].name = ' + file.name);

                file_name = file.name;
                
              }
            }
          } else {
            // Use DataTransfer interface to access the file(s)
            for (var i = 0; i < ev.dataTransfer.files.length; i++) {
              console.log('... file[' + i + '].name = ' + ev.dataTransfer.files[i].name);

              file_name = ev.dataTransfer.files[i].name
            }
          }

          //Enable button
          document.getElementById("sendFile").disabled = false;

          //Attach onclick listener to button to send file
          document.getElementById("sendFile").onclick = function() {sendFile(file_name)};

        }

        function dragOverHandler(ev) {
          console.log('File(s) in drop zone');

          // Prevent default behavior (Prevent file from being opened)
          ev.preventDefault();
        }

        function sendFile(fileName){
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                //Remove loader
                document.getElementById("loading").style.display="none";
                alert("Import successful");
                //document.getElementById("demo").innerHTML = this.responseText;
            }

            xhttp.open("POST", "/extract");
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("doc="+fileName+"&_token={{ csrf_token() }}");

            //Remove drop zone
            document.getElementById("drop_zone").style.display="none";

            //Remove button
            document.getElementById("sendFile").style.display="none";

            //Show loader
            document.getElementById("loading").style.display="block";
        }

    </script>
</body>
</html>