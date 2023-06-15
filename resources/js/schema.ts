import { z } from "zod";
export const SmsRoutingRouteCreateRequest = z.object({
    name: z.string().nonempty(),
    description: z.string().nullable().optional(),
    sms_route_company_id: z.string().nonempty(),
    smpp_connection_id: z.string().nonempty()
});
export const SmsRoutingCompanyCreateRequest = z.object({
    name: z.string().nonempty()
});
export const SmsRouteSmppConnectionCreateRequest = z.object({
    url: z.string().nonempty(),
    username: z.string().nonempty(),
    password: z.string().nonempty(),
    port: z.number().int(),
    dlr_url: z.string().nullable().optional(),
    dlr_port: z.number().nullable().int().optional(),
    workers_count: z.number().nullable().int().optional(),
    workers_delay: z.string().numeric().nullable().optional()
});
export const SmsCampaignTextCreateRequest = z.object({
    sms_campaign_id: z.number().int(),
    text: z.string().nonempty()
});
export const LoginRequest = z.object({
    email: z.string().email().nonempty(),
    password: z.string().nonempty()
});
