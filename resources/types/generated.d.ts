declare namespace App.Data {
export type SmsRoutingCompanyCreateData = {
name: string;
};
export type SmsRoutingCompanyViewData = {
id: number;
name: string;
};
export type SmsRoutingRouteCreateData = {
companyCreateData: App.Data.SmsRoutingCompanyCreateData;
selectedCompanyId: number;
selectedCompanyOption: string;
};
}
