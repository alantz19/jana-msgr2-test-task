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
                return Promise.resolve(response);
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
                return Promise.reject(error.response);
            }
        );
    }
}