class ImagenUpload {
    constructor(config) {
        this.config = {
            inputSelector: '',
            uploadUrl: '',
            tempPath: 'img/upload/temp/',
            finalPath: 'img/upload/',
            onSuccess: null,
            onError: null,
            ...config
        };

        this.input = document.querySelector(this.config.inputSelector);
        this.tempFileName = null;

        if (this.input) {
            this.input.addEventListener('change', this.handleFileSelect.bind(this));
        }
    }

    async handleFileSelect(event) {
        const file = event.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);
        formData.append('tempPath', this.config.tempPath);
        formData.append('finalPath', this.config.finalPath);

        if (this.tempFileName) {
            formData.append('previousFile', this.tempFileName);
        }

        try {
            const response = await fetch(this.config.uploadUrl, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.tempFileName = result.data.name;
                if (typeof this.config.onSuccess === 'function') {
                    this.config.onSuccess(result);
                }
            } else {
                if (typeof this.config.onError === 'function') {
                    this.config.onError(result.message);
                }
            }
        } catch (error) {
            if (typeof this.config.onError === 'function') {
                this.config.onError(error);
            }
        }
    }

    async moveToFinal() {
        if (!this.tempFileName) return;

        const formData = new FormData();
        formData.append('fileName', this.tempFileName);
        formData.append('tempPath', this.config.tempPath);
        formData.append('finalPath', this.config.finalPath);

        try {
            const response = await fetch(this.config.uploadUrl + '/move', {
                method: 'POST',
                body: formData
            });
            return await response.json();
        } catch (error) {
            console.error('Error moving file:', error);
            return { success: false, message: error.message };
        }
    }
}

// Ejemplo de uso:
// const userImageUploader = new ImagenUpload({
//     inputSelector: '#fotografia',
//     uploadUrl: '/sistema/upload',
//     tempPath: 'img/upload/personas/temp/',
//     finalPath: 'img/upload/personas/',
//     onSuccess: (result) => {
//         $("#usuario_fotografia").val(result.data.name);
//         $("#img-usuario").attr('src', PUBLIC_PATH + 'img/upload/personas/temp/' + result.data.name);
//     },
//     onError: (error) => {
//         swal.fire({
//             icon: 'error',
//             title: 'Error',
//             text: error
//         });
//     }
// });
