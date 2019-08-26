function getDataFromURL() {
    /**
     * Use this function if you need to get post id
     *
     */
    var myurl;
    var url = new URL(window.location.href); //get my url
    myurl = url.searchParams.get("post_id"); //search get parameter in the url string
    return myurl;
}
