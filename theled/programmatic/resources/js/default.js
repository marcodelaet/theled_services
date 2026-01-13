function submitUploadFile(formID){
        const formData = new FormData(); // Cria objeto FormData
        const platform = document.getElementById('platform');
        const uploadType = document.getElementById('uploadType');
        const fileInput = document.getElementById('upload-file');

        if(platform.value != "none"){
            formData.append('platform', platform.value); 
            formData.append('uploadType', uploadType.value); 
            formData.append('separator', separator.value); 
            formData.append('upload-file', fileInput.files[0]); // Adiciona o arquivo

            // Opcional: Adicionar outros dados, se precisar
            // formData.append('userId', '123');

            fetch('./api/upload/upload.csvtodatabase.dao.php', { // Caminho para o seu script PHP
                method: 'POST',
                body: formData // Envia o FormData
            })
            .then(response => response.json() ) // Espera resposta JSON
            .then(data => {
                const messageDiv = document.getElementById('message');
                const resultDiv = document.getElementById('result');
                if (data.success) {
                    messageDiv.textContent  = 'Arquivo enviado e processado com sucesso: ' + data.message;
                    messageDiv.style.color  = 'green';
                    resultDiv.textContent   = data.lines;
                    // Limpar formulário ou atualizar UI
                    document.getElementById(formID).reset();
                } else {
                    messageDiv.textContent = 'Erro ao processar: ' + data.message;
                    messageDiv.style.color = 'red';
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                document.getElementById('message').textContent = 'Ocorreu um erro na comunicação com o servidor.';
                document.getElementById('message').style.color = 'red';
            });
        } else {
            console.log('Plataforma não selecionada:');
            document.getElementById('message').textContent = 'É preciso selecionar uma plataforma para ser atualizada';
            document.getElementById('message').style.color = 'red';
        }
        
        document.getElementById('uploadButton').classList.remove('disabled');
        document.getElementById('upload-file').classList.remove('disabled');

}