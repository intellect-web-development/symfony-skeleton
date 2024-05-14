import BaseRequest from "@/api/BaseRequest";
import {client} from "@/api/client";

type classConstructor<T> = new(...args: any[]) => T

export default function requestApi<T> (
  request: BaseRequest,
  outputContractConstructor: classConstructor<T>
): Promise<T> {
  return client.request(request)
    .then(response => {
      return new outputContractConstructor(
        response.data.status,
        response.data.ok,
        {...response.data.data},
        {...response.data.messages}
      );
    });
}
