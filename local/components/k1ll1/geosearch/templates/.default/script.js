const {runComponentAction} = BX.ajax;
const {ref, reactive, createApp} = BX.Vue3;

BX.ready(() => {
    createApp({
        setup() {
            const signedParameters = document.getElementById('app')
                .getAttribute('data-params')

            let res = reactive({});
            let error = ref('');

            async function fetchUserData(e) {

                const response = await runComponentAction("k1ll1:geosearch", "fetchUserData", {
                    mode: "class",
                    data: {ip: e.target.elements.ip.value},
                    signedParameters: signedParameters
                });

                error.value = response.data.error
                Object.assign(res, response.data)
            }

            return {
                fetchUserData,
                error,
                res
            }
        },
    }).mount("#app");
});
