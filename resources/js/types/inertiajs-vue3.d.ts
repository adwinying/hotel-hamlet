declare module '@inertiajs/inertia-vue3' {
  import * as Inertia from '@inertiajs/inertia'
  import {
    Component,
    FunctionalComponent,
    Plugin,
    Ref,
  } from 'vue'

  interface AppData<PageProps extends Inertia.PageProps = Inertia.PageProps> {
    component: Component | null
    key: number | null
    props: PageProps | {}
  }

  interface AppProps<
    PagePropsBeforeTransform extends Inertia.PagePropsBeforeTransform = Inertia.PagePropsBeforeTransform,
    PageProps extends Inertia.PageProps = Inertia.PageProps
  > {
    initialPage: Inertia.Page<PageProps>
    resolveComponent: (name: string) => Component | Promise<Component>
    transformProps?: (props: PagePropsBeforeTransform) => PageProps
  }

  type App<
    PagePropsBeforeTransform extends Inertia.PagePropsBeforeTransform = Inertia.PagePropsBeforeTransform,
    PageProps extends Inertia.PageProps = Inertia.PageProps
  > = FunctionalComponent<
    AppData<PageProps>,
    never,
    never,
    AppProps<PagePropsBeforeTransform, PageProps>
  >

  interface InertiaLinkProps {
    as?: string
    data?: object
    href: string
    method?: string
    headers?: object
    onClick?: (event: MouseEvent | KeyboardEvent) => void
    preserveScroll?: boolean | ((props: Inertia.PageProps) => boolean)
    preserveState?: boolean | ((props: Inertia.PageProps) => boolean) | null
    replace?: boolean
    only?: string[]
    onCancelToken?: (cancelToken: import('axios').CancelTokenSource) => void
    onBefore?: () => void
    onStart?: () => void
    onProgress?: (progress: number) => void
    onFinish?: () => void
    onCancel?: () => void
    onSuccess?: () => void
  }

  interface Form {
    errors: Record<string, unknown>;
    hasErrors: boolean,
    processing: boolean,
    progress: null,
    wasSuccessful: boolean,
    recentlySuccessful: boolean,

    data(): Record<string, unknown>;
    transform(callback: (data: Record<string, unknown>) => Record<string, unknown>): Form;
    reset(...fields: string[]): Form;
    clearErrors(...fields: string[]): Form;
    submit(method: string, url: string, options?: Record<string, unknown>): void;
    get(url: string, options?: Record<string, unknown>): void;
    post(url: string, options?: Record<string, unknown>): void;
    put(url: string, options?: Record<string, unknown>): void;
    patch(url: string, options?: Record<string, unknown>): void;
    delete(url: string, options?: Record<string, unknown>): void;
  }

  type UseForm = Ref<Form>

  type InertiaLink = FunctionalComponent<InertiaLinkProps>

  export const InertiaLink: InertiaLink

  export const InertiaApp: App

  export const App: App

  export const plugin: Plugin

  export function useForm(data: Record<string, unknown>): UseForm
}
