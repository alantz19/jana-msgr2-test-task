export type SmsRoutingRouteCreateRequest = {
    name: string;
    description?: string;
    selectedCompanyId: string;
    smppConnectionDataId: string;
};
export type SmsRoutingCompanyCreateRequest = {
    name: string;
};
export type SmsRouteSmppConnectionCreateRequest = {
    url: string;
    username: string;
    password: string;
    port: number;
    dlr_url?: string;
    dlr_port?: number;
    workers_count?: number;
    workers_delay?: string;
};
export type SmsCampaignTextCreateRequest = {
    sms_campaign_id: number;
    text: string;
};
