import {projectName} from "@/router/routerDictionary";

export interface BreadcrumbItem {
    label: string;
    command?: () => void;
}

export interface Breadcrumbs {
    items: BreadcrumbItem[];
}

export function generateTitleFromBreadcrumbs(breadcrumbs: Breadcrumbs) {
    return breadcrumbs.items.slice().reverse().map((item) => item.label).join(' | ') + ' | ' + projectName;
}

export function generateTitleForDetailsPage(boundedContextName: string|null, entityName: string|null, entityId: string|null) {
    return `${entityName} #${entityId} | ${boundedContextName} | ${projectName}`;
}