<?php
    if(isset($_POST['doc'])){
        // Using PHP Word library
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($_POST['doc']);

        // Saving the document as HTML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
        $objWriter->save('temp.html');

        //load temporary html file
        $doc = new DOMDocument();
        $doc->loadHTMLFile("temp.html");

        //Fetching all rows
        $imgs = $doc->getElementsByTagName('img');
        $i = 1;
        $side = "left";

        
        /////////EDIT HERE ////////////////////////////////////////

        //Extract image tag contents and save to directory or database
        function saveImage($data,$count) {
            //Splitting 'data:image/png;base64,' from data
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);

            //Create directory tmp
            if ( !is_dir( "tmp" ) ) {
                mkdir( "tmp" );       
            }

            file_put_contents('tmp/image'.$count.'.png', $data);
        }



        //looping through rows of each table
        foreach ($imgs as $img) {

            if($side=="left"){
                //Fetching example:
                //$data = 'data:image/png;base64,AAAFBfj42Pj4';
                $data = $img->getAttribute('src');
                saveImage($data, $i);
                $i++;
                $side="right";
            } else if ($side=="right") {
                $side="left";
            }
        }

        $side = "left";

        //Fetching left images from child nodes of row
        //looping through rows of each table
        foreach ($imgs as $img) {

            if($side=="left"){
                $side="right";
            } else if ($side=="right") {
                //Fetching example:
                //$data = 'data:image/png;base64,AAAFBfj42Pj4';
                $data = $img->getAttribute('src');
                saveImage($data, $i);
                $i++;
                $side="left";
            }
        }

    }
?>
