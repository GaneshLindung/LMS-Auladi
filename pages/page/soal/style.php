<style>
    .pilgand {
        padding: 16px 10px;
        border: 1px solid #DFDFDF;
        border-radius: 5px;
    } .pilgand:not(:last-child) {margin-bottom: 13px;}

    span.input-group-addon {font-weight: bold;cursor: pointer;}

    .answers .input-group.selected .input-group-addon {
        color: blue;
    }

    .answers .choices {display: grid;gap: 17px;}

    img.taskImage {width: 100%;max-width: 350px;margin-top: 10px;}

    @media (max-width: 768px) {
        .answers {margin-top: 16px;}
    } @media (min-width: 768px) {
        .answers .choices {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>