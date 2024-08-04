import BaseRequest from "@/api/common/BaseRequest";
import {client} from "@/api/common/client";

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
