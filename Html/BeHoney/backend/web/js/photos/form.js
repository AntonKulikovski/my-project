/* JS logic of file uploading */
$(function () {
    var UNIQUE_ID_PARAM = '__uniqueIdentificator',
        INTERNAL_POSITION = '__internalPosition',
        FORM_SELECTOR = '#package-form',
        files = {},
        NAME_FIELD = $(FORM_SELECTOR).data('name-field'),
        position = 0;

    $(document).on('fileAdded', FORM_SELECTOR, function (event, file, response) {
        if (!response.success) {
            alert('Internal server upload error!');
        }

        if (response.file[INTERNAL_POSITION] !== undefined) {
            position = Math.max(position, response.file[INTERNAL_POSITION]);
        } else {
            response.file[INTERNAL_POSITION] = ++position;
        }

        var fileValue = response.file.value;
        files[fileValue] = response.file;
        file[UNIQUE_ID_PARAM] = fileValue;
    });
    $(document).on('fileRemoved', FORM_SELECTOR, function (event, file) {
        var fileValue = file[UNIQUE_ID_PARAM];
        if (fileValue) {
            files[fileValue] = false;
        }
    });

    $(document).on('submit', FORM_SELECTOR, function () {
        var $form = $(this),
            storagePrefix = $form.data('prefix'),
            fileValue,
            $hidden,
            index = 1;

        var hiddens = new Array(position);
        for (fileValue in files) {
            if (files.hasOwnProperty(fileValue) && files[fileValue]) {
                $hidden = $('<input type="hidden" name="' + NAME_FIELD + '[photosModels][' + index++ + '][imageFile]" />');
                $hidden.val(storagePrefix + fileValue);
                hiddens[files[fileValue][INTERNAL_POSITION]] = $hidden;
            }
        }

        $form.append('<input type="hidden" name="' + NAME_FIELD + '[photosModels][][imageFile]" value=""/>');

        for (var i = 0, count = hiddens.length; i < count; ++i) {
            hiddens[i] && $form.append(hiddens[i]);
        }
    });
});
