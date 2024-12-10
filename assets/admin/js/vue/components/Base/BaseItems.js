import {ref } from 'vue'

// on met à jour les positions des 2 items en base
export function useAjaxSwitchPosition(uri, item1, item2){
    const requestOptions = {
    method: "POST",
    headers: { 
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'Authorization': 'Bearer my-token',
    },
    body: JSON.stringify({'id1': item1['id'],  'id2': item2['id'] })
  };
  fetch(uri, requestOptions)
    .then(response => response.json())
}
