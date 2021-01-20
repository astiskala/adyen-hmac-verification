using System; 
using System.Security.Cryptography;
using System.Text;

namespace VerifyHmac
{
    class Program
    {
        static void Main(string[] args)
        {
          if (args == null || args.Length == 0)
          {
              Console.WriteLine("Please specify 3 arguments. String to sign, HMAC key and the provided signature.");
          }
          else
          {
              var signingstring = arg[0];
              var hmacKey = arg[1];
              var expectedResult = arg[2];
            
              var result = ValidateHMACKey(signingstring, hmacKey, expectedResult);
              Console.WriteLine(result ? "HMAC is a match!" : "HMAC did not match");
          }
          
          Console.ReadKey();          
        }

        private static bool ValidateHMACKey(string signingstring, string hmacKey, string expectedResult)
        {
            byte[] key = PackH(hmacKey);
            byte[] data = Encoding.UTF8.GetBytes(signingstring);

            try
            {
                using (HMACSHA256 hmac = new HMACSHA256(key))
                {
                    // Compute the hmac on input data bytes
                    byte[] rawHmac = hmac.ComputeHash(data);

                    // Base64-encode the hmac
                    var hmacSignature = Convert.ToBase64String(rawHmac);

                    if (hmacSignature == expectedResult)
                        return true;
                    else
                        return false;
                }
            }
            catch (Exception e)
            {
                throw new Exception("Failed to generate HMAC : " + e.Message);
            }
        }

        private static byte[] PackH(string hex)
        {
            if ((hex.Length % 2) == 1)
            {
                hex += '0';
            }

            byte[] bytes = new byte[hex.Length / 2];
            for (int i = 0; i < hex.Length; i += 2)
            {
                bytes[i / 2] = Convert.ToByte(hex.Substring(i, 2), 16);
            }

            return bytes;
        }
    }
}
