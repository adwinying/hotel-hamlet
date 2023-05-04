{
  description = "Hotel Hamlet";

  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, flake-utils }:
    flake-utils.lib.eachDefaultSystem (system: let
      pkgs = nixpkgs.legacyPackages.${system};
    in {
      devShell = with pkgs; mkShell {
        buildInputs = [
          php82
          php82Packages.composer
          nodejs_16
        ];

        shellHook = ''
          echo "`${pkgs.php82}/bin/php --version`"
          echo "`${pkgs.php82Packages.composer}/bin/composer --version`"
          echo "node: `${pkgs.nodejs_16}/bin/node --version`"
          echo "npm: v`${pkgs.nodejs_16}/bin/npm --version`"
        '';
      };
    });
}
