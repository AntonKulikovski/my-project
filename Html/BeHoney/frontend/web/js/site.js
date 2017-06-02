$(document).ready(function () {
    var $document = $(document);
    var pageMed = 1;
    var pageOtherMed = 1;
    var pagePackage = 1;

    $('[data-slug=med]').on('click', function(){
        var pageItem = pageMed * 3;
        var $this = $(this);
        $this.parents('.itemCategory').find('[data-index=' + pageItem + ']').show();
        pageItem++;

        if (pageItem >= Number($this.attr('data-count'))) {
            $this.hide();
            return true;
        }

        $this.parents('.itemCategory').find('[data-index=' + pageItem + ']').show();
        pageItem++;

        if (pageItem >= Number($this.attr('data-count'))) {
            $this.hide();
            return true;
        }

        $this.parents('.itemCategory').find('[data-index=' + pageItem + ']').show();
        pageMed++;
        pageItem++;

        if (pageItem >= Number($this.attr('data-count'))) {
            $this.hide();
            return true;
        }
    });

    $('[data-slug=vzbityj]').on('click', function(){
        var pageItem = pageOtherMed * 3;
        var $this = $(this);
        $this.parents('.itemCategory').find('[data-index=' + pageItem + ']').show();
        pageItem++;

        if (pageItem >= Number($this.attr('data-count'))) {
            $this.hide();
            return true;
        }

        $this.parents('.itemCategory').find('[data-index=' + pageItem + ']').show();
        pageItem++;

        if (pageItem >= Number($this.attr('data-count'))) {
            $this.hide();
            return true;
        }

        $this.parents('.itemCategory').find('[data-index=' + pageItem + ']').show();
        pageOtherMed++;
        pageItem++;

        if (pageItem >= Number($this.attr('data-count'))) {
            $this.hide();
            return true;
        }
    });

    $('[data-slug=package]').on('click', function(){
        var pageItem = pagePackage * 3;
        var $this = $(this);
        $this.parents('.itemPackage').find('[data-index=' + pageItem + ']').show();
        pageItem++;

        if (pageItem >= Number($this.attr('data-count'))) {
            $this.hide();
            return true;
        }

        $this.parents('.itemPackage').find('[data-index=' + pageItem + ']').show();
        pageItem++;

        if (pageItem >= Number($this.attr('data-count'))) {
            $this.hide();
            return true;
        }

        $this.parents('.itemPackage').find('[data-index=' + pageItem + ']').show();
        pagePackage++;
        pageItem++;

        if (pageItem >= Number($this.attr('data-count'))) {
            $this.hide();
            return true;
        }
    });
});