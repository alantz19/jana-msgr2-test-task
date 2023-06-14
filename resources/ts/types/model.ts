export type WorldMobileNetwork = {
    id: number;
    mcc?: number;
    mnc?: number;
    type?: string;
    country_name?: string;
    country_code?: string;
    world_country_id?: number;
    brand?: string;
    operator?: string;
    status?: string;
    bands?: string;
    notes?: string;
};
export type WorldCountry = {
    id: number;
    iso?: string;
    name?: string;
    nicename?: string;
    iso3?: string;
    numcode?: number;
    phonecode?: number;
    has_states: number;
    sender_id: number;
    created_at?: string;
    updated_at?: string;
};
export type User = {
    id: any;
    name: string;
    email: string;
    email_verified_at?: string;
    current_team_id?: any;
    profile_photo_path?: string;
    created_at?: string;
    updated_at?: string;
    two_factor_confirmed_at?: string;
    profile_photo_url: any;
    current_team?: Team;
    owned_teams?: Team[];
    teams?: Team[];
};
export type TeamInvitation = {
    id: any;
    team_id: any;
    email: string;
    role?: string;
    created_at?: string;
    updated_at?: string;
    team?: Team;
};
export type Team = {
    id: any;
    user_id: any;
    name: string;
    personal_team: boolean;
    created_at?: string;
    updated_at?: string;
    lists?: Lists[];
    sms_routing_plans?: SmsRoutingPlan[];
    sms_routing_plan_connections_seller?: SmsRoutePlatformConnection[];
    sms_routing_plan_connections_customer?: SmsRoutePlatformConnection[];
    sms_routing_platform_routes?: CustomerRoute[];
};
export type SmsRoutingPlanRoutes = {
    id: any;
    sms_route_id: any;
    sms_routing_plan_id: any;
    is_active: boolean;
    priority: string;
    meta?: any;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
};
export type SmsRoutingPlan = {
    id: any;
    team_id: any;
    name: string;
    is_platform_plan: boolean;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
    connections?: SmsRoutePlatformConnection[];
    sms_routes?: SmsRoute[];
};
export type SmsRouteSmppConnection = {
    id: any;
    url: string;
    username?: string;
    password?: string;
    port?: string;
    dlr_url?: string;
    dlr_port?: string;
    workers_count?: number;
    workers_delay?: any;
    deleted_at?: string;
    created_at?: string;
    updated_at?: string;
    route?: SmsRoute;
};
export type SmsRouteRate = {
    id: any;
    sms_route_id: any;
    world_country_id: number;
    rate: any;
    meta?: any;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
};
export type SmsRoutePlatformConnection = {
    id: any;
    name?: string;
    sms_routing_plan_id: any;
    customer_team_id: any;
    rate_multiplier: any;
    is_active: boolean;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
};
export type SmsRouteCompany = {
    id: any;
    team_id: any;
    name: string;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
};
export type SmsRoute = {
    id: any;
    team_id: any;
    name: string;
    sms_route_company_id: any;
    connection_type?: string;
    connection_id?: any;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
    is_active: boolean;
    smpp_connection?: any;
    connection?: any;
    sms_route_rates?: SmsRouteRate[];
    team?: Team;
    sms_route_company?: SmsRouteCompany;
};
export type SmsCampaignText = {
    id: any;
    sms_campaign_id: any;
    text: string;
    is_active: boolean;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
};
export type SmsCampaignSenderid = {
    id: any;
    sms_campaign_id: any;
    text: string;
    is_active: boolean;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
};
export type SmsCampaignSend = {
    id: any;
    sms_campaign_id: any;
    status: string;
    meta?: string;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
    campaign?: SmsCampaign;
};
export type SmsCampaignPlan = {
    id: any;
    team_id: any;
    name: string;
    meta?: string;
    deleted_at?: string;
    created_at?: string;
    updated_at?: string;
};
export type SmsCampaign = {
    id: any;
    team_id: any;
    name: string;
    status: string;
    next_send_at?: string;
    sms_campaign_plan_id?: any;
    meta?: string;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
    team?: Team;
    sends?: SmsCampaignSend[];
    offers?: Offer[];
};
export type Offer = {
    id: any;
    team_id: any;
    name: string;
    url: string;
    profit?: number;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
};
export type Membership = {
    id: number;
    team_id: any;
    user_id: any;
    role?: string;
    created_at?: string;
    updated_at?: string;
};
export type Lists = {
    id: any;
    team_id: any;
    name: string;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
};
export type Domain = {
    id: number;
    domain: string;
    is_active: boolean;
    team_id?: any;
    meta?: any;
    created_at?: string;
    updated_at?: string;
};
export type CustomerRoute = {
    id: any;
    team_id: any;
    name: string;
    sms_route_company_id: any;
    connection_type?: string;
    connection_id?: any;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
    is_active: boolean;
    sms_route_rates?: SmsRouteRate[];
};
enum SmsCampaignStatusEnum {
}
