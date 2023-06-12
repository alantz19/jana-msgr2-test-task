declare namespace App.Data {
export type SmppConnectionData = {
url: string;
port: number;
username: string;
password: string;
dlrUrl: string | null;
dlrPort: number | null;
is_tested: boolean | null;
};
export type SmsRoutingCompanyCreateData = {
name: string;
};
export type SmsRoutingCompanyViewData = {
id: number;
name: string;
};
export type SmsRoutingRouteCreateData = {
companyCreateData: App.Data.SmsRoutingCompanyCreateData | null;
selectedCompanyId: number | null;
selectedCompanyOption: string;
smppConnectionData: App.Data.SmppConnectionData | null;
};
}
