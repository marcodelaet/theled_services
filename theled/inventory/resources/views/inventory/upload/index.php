<!--home : inventory - new -->

<div id="<?= $currentPage ?>">
    Home : <?= ucfirst($tracking) ?>
    <P>
        <div id="div-form-<?=$currentPage ?>">
            <div id="message"></div>
            <form id="formUpload" method="POST" enctype="multipart/form-data" >
                <div class="mb-3">
                    <select class="form-select" aria-label="Choose base platform" id="platform">
                        <option value="none">Select Platform</option>                        
                        <option value="admooh">Admooh</option>
                        <option value="adsmovil">Adsmovil</option>
                        <option value="hivestack">Hivestack</option>
                        <option value="invian">Invian</option>
                        <option value="onsign">OnSign</option>  
                        <option value="outcon">Outcon</option>                       
                    </select>
                </div>
                <div class="mb-3">
                    <select class="form-select" aria-label="Choose base type" id="uploadType">
                        <option value="screens">Screens</option>                        
                        <option value="sites">Sites</option>
                    </select>
                </div>
                <div class="mb-3">
                    <select class="form-select" aria-label="Separator" id="separator">                       
                        <option value=";">ponto e virgula (;)</option>
                        <option value=",">virgula (,)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="upload-file">Choose your file to upload</label>
                    <input class="form-control" type="file" id="upload-file">
                </div>
                <div class="d-grid gap-2">
                    <button id="uploadButton" class="btn btn-primary" onclick="submitUploadFile('formUpload');document.getElementById('upload-file').classList.add('disabled'); this.classList.add('disabled')" type="button">Upload</button>
                </div>
            </form>
        </div>
    </P><p>
        <div id="result"></div>
    </p>
</div>
