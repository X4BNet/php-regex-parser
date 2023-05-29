{
  description = "terebinth";

  inputs = {
    #nixpkgs.url = "github:nixos/nixpkgs/nixos-23.05";
    flake-utils = {
      url = "github:numtide/flake-utils";
    };
  };

  outputs = {
    self,
    nixpkgs,
    flake-utils,
  }:
    flake-utils.lib.eachDefaultSystem (system: let
      pkgs = nixpkgs.legacyPackages.${system};
      php = pkgs.php81;
      phpWithPcov = php.withExtensions ({
        all,
        enabled,
        ...
      }:
        enabled ++ (with all; [pcov]));
    in rec {
      devShells.default = pkgs.mkShell {
        buildInputs = with pkgs; [
          actionlint
          alejandra
          phpWithPcov
          phpWithPcov.packages.composer
        ];
        shellHook = ''
          export PATH="$PWD/vendor/bin:$PATH"
        '';
      };

      formatter = pkgs.alejandra;
    });
}
