// Saves options to chrome.storage
function save_options() {
    var api_key = document.getElementById('api_key').value;
    localStorage.setItem('api_key', api_key); 
    var user_id = document.getElementById('user_id').value;
    localStorage.setItem('user_id', user_id);    
   
}

// Restores select box and checkbox state using the preferences
// stored in chrome.storage.
function restore_options() {
    api_key = localStorage.getItem('api_key');
    document.getElementById('api_key').value = api_key;
    user_id = localStorage.getItem('user_id');
    document.getElementById('user_id').value = user_id;
    
}
document.addEventListener('DOMContentLoaded',restore_options);
document.getElementById('save').addEventListener('click',save_options);