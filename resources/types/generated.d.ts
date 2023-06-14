declare namespace App.Data {
export type FlashData = {
type: string;
title: string;
message: any | string;
};
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
export type SmsRoutingCompanyData = {
id: string;
name: string;
};
export type SmsRoutingRouteCreateData = {
name: string;
description: string | null;
companyCreateData: App.Data.SmsRoutingCompanyCreateData | null;
selectedCompanyId: number | null;
selectedCompanyOption: string;
smppConnectionData: App.Data.SmppConnectionData | null;
};
}
