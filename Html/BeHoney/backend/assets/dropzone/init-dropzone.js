window.dzr = window.dzr || {};
window.dzr.initDropZone = function (dropzone, files) {
    $(dropzone.element).closest('form').on('submit', (function () {
        var stopped = null;
        return function (event) {
            if (stopped !== null) {
                stopped && event.stopImmediatePropagation();
                return !stopped;
            }

            var activeFiles = dropzone.getActiveFiles();
            if (!activeFiles || !activeFiles.length) {
                return;
            }

            var msg = 'Not all files have been uploaded! ' +
                'If you continue some images can not be saved.\n' +
                'Are you sure to continue?';
            var result;
            if (!confirm(msg)) {
                event.stopImmediatePropagation();
                result = false;
            } else {
                result = true;
            }

            stopped = !result;
            setTimeout(function () {
                stopped = null;
            }, 500);
            return result;
        };
    })());

    if (!files.length) {
        return;
    }

    setTimeout(function () {
        var i, length;
        for (i = 0, length = files.length; i < length; ++i) {
            files[i].__internalPosition = i;
            dropzone.files.push(files[i]);
            dropzone.emit('addedfile', files[i]);
            dropzone.emit('success', files[i], {success: true, file: files[i]});
            dropzone.emit('complete', files[i]);
            if (files[i].type && /^image/.test(files[i].type) && files[i].url) {
                dropzone.options.thumbnail.call(dropzone, files[i], files[i].url);
            }
        }
    }, 1);
};
