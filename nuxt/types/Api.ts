import {V1} from "~/types/V1";

export class Api<SecurityDataType = unknown> extends V1<SecurityDataType> {

    constructor() {
        super();
        // let token = useCookie("XSRF-TOKEN").value;
        // this.instance.defaults.headers = useRequestHeaders(["cookie"]);
        // this.instance.defaults.headers = {
        //     accept: "application/json",
        //     ...(token && {['X-XSRF-TOKEN']: token}),
        //     ...useRequestHeaders(["cookie"]),
        //     referer: 'http://localhost:3000',
        // };
        this.instance.defaults.withCredentials = true;

        this.instance.interceptors.response.use(
            response => {
                return Promise.resolve(response.data);
            },
            error => {
                const status = error.response?.status ?? -1;

                if ([401, 419].includes(status)) {
                    useRouter().push("/login");
                }

                if ([409].includes(status)) {
                    useRouter().push("/verify-email");
                }

                if ([500].includes(status)) {
                    console.error("[API Error]", error.data?.message, error.data);
                }
                /**
                 // Handle error responses here
                 if (error.response) {
                    // The request was made and the server responded with a status code
                    console.log('Status code:', error.response.status);
                    console.log('Response data:', error.response.data);
                } else if (error.request) {
                    // The request was made but no response was received
                    console.log('No response received:', error.request);
                } else {
                    // Something happened in setting up the request that triggered an error
                    console.log('Error:', error.message);
                }
                 **/
                // Return a rejected Promise to propagate the error to the next `catch` block
                return Promise.reject(error.response);
            }
        );
    }
}